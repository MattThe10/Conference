<?php

namespace App\Http\Controllers;

use App\Models\ArticleStatus;
use Illuminate\Http\Request;

class ArticleStatusController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Retrieve all article_status
    |--------------------------------------------------------------------------
    |
    | This method fetches all records from the `article_statuses` table and 
    | returns them as a JSON response.
    |
    */

    public function index(Request $request) 
    {
        // Initialize the query  
        $article_statuses = ArticleStatus::query(); 
            
        //Check if there a 'search' parameter in the request 
        if ($request->has('search') && $request->search !=   null) {
        
            // Convert the search term to lowercase for case-insensitive matching 
            $search = strtolower($request->search); 
            
            // Apply filtering based on the search term 
            $article_statuses = $article_statuses->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(`key`) LIKE ?', ['%' . $search . '%']) // Filter by key
                    ->orwhereRaw('LOWER(name) LIKE?', ['%' . $search . '%']); // Filter by name 
            });
        }   
                    
        $article_statuses = $article_statuses->get(); 
                
        // Return the resulting article_statuses as a JSON response 
        return response()->json($article_statuses);
    }

    /*
    |--------------------------------------------------------------------------
    | Retrieve a specific article_status by its ID
    |--------------------------------------------------------------------------
    |
    | This method fetches a article_status record based on the provided ID and 
    | returns it as a JSON response. If the article_status is not found, it
    | returns `null`.
    |
    */

    public function show($article_status_id)
    {
        $article_status = ArticleStatus::findOrFail($article_status_id);

        return response()->json($article_status);
    }

    /*
    |--------------------------------------------------------------------------
    | Store a newly created article_status in the database.
    |--------------------------------------------------------------------------
    |
    | This method validates the request data and creates a new article_status
    | record in the `article_statuses` table. It returns a 201 status code upon
    | successful creation.
    |
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key'   => ['required', 'string'],
            'name'  => ['nullable', 'string', 'max:255'],
        ]);

        ArticleStatus::create([
            'key'   => $validated['key'],
            'name'  => $validated['name'] ?? null,
        ]);

        return response()->json([
            'message' => 'Successfully stored.'
        ], 201); // 201 CREATED
    }

    /*
    |--------------------------------------------------------------------------
    | Update the specified article_status in the database.
    |--------------------------------------------------------------------------
    |
    | This method validates the request data, finds the specified article_status
    | by its ID, and updates its information. It returns a 200 status code
    | upon successful update.
    |
    */

    public function update(Request $request, $article_status_id)
    {
        $validated = $request->validate([
            'key'   => ['required', 'string'],
            'name'  => ['nullable', 'string', 'max:255'],
        ]);

        $article_status = ArticleStatus::findOrFail($article_status_id);

        $article_status->update([
            'key'   => $validated['key'],
            'name'  => $validated['name'] ?? null,
        ]);

        return response()->json([
            'message' => 'Successfully updated.'
        ], 200); // 200 OK
    }

    /*
    |--------------------------------------------------------------------------
    | Remove the specified article_status from the database.
    |--------------------------------------------------------------------------
    |
    | This method deletes the specified article_status record based on the provided ID.
    | It returns a 200 status code upon successful deletion.
    |
    */

    public function destroy($article_status_id)
    {
        $article_status = ArticleStatus::findOrFail($article_status_id);
        $article_status->delete();

        return response()->json([
            'message' => 'Successfully deleted.'
        ], 200); // 200 OK
    }
}
