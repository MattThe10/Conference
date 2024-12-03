<?php

namespace App\Http\Controllers;

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

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with(['article_status', 'conference', 'documents', 'reviews', 'users'])->get();

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
            'conferences_id'        => $validated['conference_id'] ?? Conference::latest()->first()->id,
        ]);

        $user->articles()->attach($article->id);

        return response()->json([
            'message'   => 'Article created successfully.',
        ], 201);
    }

    public function update(Request $request, $article_id)
    {
        $validated = $request->validate([
            'title'             => ['required', 'string', 'max:255'],
            'file_pdf'          => ['required', 'file', 'mimes:pdf'],
            'file_word'         => ['required', 'file', 'mimes:doc,docx'],
            'user_ids'          => ['array', 'exists:users,id'],
            'article_status_id' => ['integer', 'exists:article_statuses,id'],
            'conference_id'     => ['integer', 'exists:conferences,id'],
        ]);

        DB::beginTransaction();
        try {
            $article = Article::findOrFail($article_id);
            $article->update([
                'title'                 => $validated['title'],
                'article_statuses_id'   => $validated['article_status_id'] ?? ArticleStatus::where('key', 'submitted')->first()->id,
                'conferences_id'        => $validated['conference_id'] ?? Conference::latest()->first()->id,
            ]);

            foreach (['file_pdf', 'file_word'] as $file_type) {
                $file = $validated[$file_type];
                $file_path = $file->store('documents', 'public');
                Document::create([
                    'name'          => $file->getClientOriginalName(),
                    'path'          => $file_path,
                    'ext'           => $file->getClientOriginalExtension(),
                    'articles_id'   => $article->id,
                ]);
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
}
