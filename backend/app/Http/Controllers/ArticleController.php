<?php

namespace App\Http\Controllers;

use App\Exports\ArticlesExport;
use App\Models\Article;
use App\Models\ArticleStatus;
use App\Models\Conference;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class ArticleController extends Controller
{
    public function index(Request $request) 
	{
		// Initialize the query  
        //$articles = Article::with(['article_status', 'conference', 'documents', 'reviews', 'users'])->get();

		$articles = Article::query()
		    ->with(['conference', 'reviews', 'users', 'article_status']); 
			
		//Check if there a 'search' parameter in the request 
		if ($request->has('search') && $request->search != null) {
		
			// Convert the search term to lowercase for case-insensitive matching 
			$search = strtolower($request->search); 
			
			// Apply filtering based on the search term 
			$articles = $articles->where(function ($query) use ($search) {
				$query->whereRaw('LOWER(title) LIKE ?', ['%' . $search . '%']); // Filter by title
			});
		}	
					
		$articles = $articles->get(); 
				
		// Return the resulting articles as a JSON response 
		return response()->json($articles);
	}

    public function show($article_id)
    {
        $article = Article::with(['article_status', 'conference', 'documents', 'reviews', 'users'])->findOrFail($article_id);

        return response()->json($article);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'             => ['string', 'max:255'],
            'article_status_id' => ['integer', 'exists:article_statuses,id'],
            'conference_id'     => ['integer', 'exists:conferences,id'],
        ]);

        $user = User::findOrFail($request->user_id);

        $article = Article::create([
            'title'                 => $validated['title'] ?? 'New Article',
            'article_statuses_id'   => $validated['article_status_id'] ?? ArticleStatus::where('key', 'draft')->first()->id,
            'conferences_id'        => $validated['conference_id'] ?? Conference::orderBy('id', 'desc')->first()->id,
        ]);

        $user->articles()->attach($article->id);

        return response()->json([
            'message'   => 'Article created successfully.',
        ], 201);
    }

    public function update(Request $request, $article_id)
    {Log::info($request);
        $validated = $request->validate([
            'title'             => ['required', 'string', 'max:255'],
            'file_pdf'          => ['file', 'mimes:pdf'],
            'file_word'         => ['file', 'mimes:doc,docx'],
            'user_ids'          => ['array'],
            'user_ids.*'        => ['exists:users,id'],
            'article_status_id' => ['integer', 'exists:article_statuses,id'],
            'conference_id'     => ['required', 'integer', 'exists:conferences,id'],
        ]);

        DB::beginTransaction();
        try {
            $article = Article::findOrFail($article_id);
            $article->update([
                'title'                 => $validated['title'],
                'article_statuses_id'   => $validated['article_status_id'] ?? ArticleStatus::where('key', 'submitted')->first()->id,
                'conferences_id'        => $validated['conference_id'],
            ]);

            foreach (['file_pdf', 'file_word'] as $file_type) {
                if (isset($validated[$file_type])) {
                    $file = $validated[$file_type];Log::info($file->getClientOriginalName());
                    $file_path = $file->store('documents', 'public');
                    Document::updateOrCreate(
                        [
                            'ext'           => $file->getClientOriginalExtension(),
                            'articles_id'   => $article->id,
                        ],
                        [
                            'name'          => $file->getClientOriginalName(),
                            'path'          => $file_path,
                        ]
                    );
                }
            }

            if (isset($validated['user_ids'])) {
                $article->users()->sync($validated['user_ids']);
            }

            DB::commit();

            return response()->json([
                'message' => 'Article and documents created successfully.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create article and documents: ' . $e->getMessage());

            return response()->json([
                'message' => 'Failed to create article and documents.'
            ], 500);
        }
    }

    public function destroy($article_id)
    {
        DB::beginTransaction();
        try {
            $article = Article::findOrFail($article_id);
            $documents = $article->documents;

            $backup_paths = [];
            foreach ($documents as $document) {
                $backup_path = 'backup/' . $document->path;
                Storage::disk('public')->move($document->path, $backup_path);
                $backup_paths[] = ['original' => $document->path, 'backup' => $backup_path];
            }

            $article->documents()->delete();
            $article->users()->detach();
            $article->delete();

            DB::commit();

            foreach ($backup_paths as $backup_path) {
                if (Storage::disk('public')->exists($backup_path['backup'])) {
                    Storage::disk('public')->delete($backup_path['backup']);
                }
            }

            return response()->json([
                'message' => 'Article and documents deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            foreach ($backup_paths as $backup_path) {
                if (Storage::disk('public')->exists($backup_path['backup'])) {
                    Storage::disk('public')->move($backup_path['backup'], $backup_path['original']);
                }
            }

            Log::error('Failed to delete article and documents: ' . $e->getMessage());

            return response()->json([
                'message' => 'Failed to delete article and documents.'
            ], 500);
        }
    }

    public function download(Request $request)
    {
        $validated = $request->validate([
            'article_ids'   => ['required', 'array'],
            'article_ids.*' => ['integer', 'exists:articles,id'],
        ]);

        $all_documents = collect();
        $data = [];

        foreach ($validated['article_ids'] as $article_id) {
            $article = Article::findOrFail($article_id);
            $documents = $article->documents;
            $all_documents = $all_documents->merge($documents);

            $article_data = [
                'id'        => $article->id,
                'title'     => $article->title,
                'status'    => $article->article_status->name,
                'authors'   => [],
            ];

            foreach ($article->users as $user) {
                $user_data = [
                    'id'            => $user->id,
                    'name'          => $user->name,
                    'surname'       => $user->surname,
                    'email'         => $user->email,
                    'role'          => $user->role->name,
                    'university'    => $user->faculty->university->name,
                    'faculty'       => $user->faculty->name,
                ];

                $article_data['authors'][] = $user_data;
            }

            $data[] = $article_data;
        } 

        if ($all_documents->isNotEmpty()) {
            $excel_file_name = 'article_list_' . uniqid() . '.xlsx';
            $excel_path = storage_path('app/public/' . $excel_file_name);

            Excel::store(new ArticlesExport($data), $excel_file_name, 'public');

            $zip_file_name = 'article_list_' . uniqid() . '.zip';
            $zip = new ZipArchive();

            if ($zip->open($zip_file_name, ZipArchive::CREATE | ZipArchive::OVERWRITE) != true) {
                Log::error("Could not create ZIP file.");
            }

            if (in_array(Auth::user()->role->key, ['super_admin', 'admin']) && file_exists($excel_path)) {
                $zip->addFile($excel_path, 'article_list.xlsx');
            }

            foreach ($all_documents as $document) {
                $zip_path = storage_path('app/public/' . $document->path);

                if (file_exists($zip_path)) {
                    $relative_path = $document->articles_id . '/' . basename($document->path);
                    $zip->addFile($zip_path, $relative_path);
                }
            }

            $zip->close();

            if (file_exists($excel_path)) {
                unlink($excel_path);
            }

            return response()->streamDownload(function () use ($zip_file_name) {
                readfile($zip_file_name);
                if (file_exists($zip_file_name)) {
                    unlink($zip_file_name);
                }
            });
        }

        return response()->json([
            'message' => 'No files found for the specified type.'
        ], 404);
    }
}
