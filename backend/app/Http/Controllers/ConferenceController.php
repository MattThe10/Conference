<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConferenceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Retrieve all conferences
    |--------------------------------------------------------------------------
    |
    | This method fetches all records from the `conferences` table and 
    | returns them as a JSON response.
    |
    */
    
    public function index(Request $request) 
    {
        // Initialize the query
        $conferences = Conference::query()
            ->with(['university', 'articles.documents']);
            
        //Check if there a 'search' parameter in the request 
        if ($request->has('search') && $request->search !=   null) {

            // Convert the search term to lowercase for case-insensitive matching 
            $search = strtolower($request->search); 
        
            // Apply filtering based on the search term 
            $conferences = $conferences->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', '%' . $search . '%') // title
                      ->orWhereHas('university', function ($query) use ($search) {
                        $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']); // Filter by related university name
                    });
            });             
        }   
                    
        $conferences = $conferences->get(); 
                
        // Return the resulting conferences as a JSON response 
        return response()->json($conferences);
    }

    /*
    |--------------------------------------------------------------------------
    | Retrieve a specific conference by its ID
    |--------------------------------------------------------------------------
    |
    | This method fetches a conference record based on the provided ID and 
    | returns it as a JSON response. If the conference is not found, it
    | returns `null`.
    |
    */

    public function show($conference_id)
    {
        $conference = Conference::with(['articles.conference', 'articles.article_status', 'articles.reviews', 'articles.documents', 'articles.users'])
            ->findOrFail($conference_id);

        return response()->json($conference);
    }

    /*
    |--------------------------------------------------------------------------
    | Store a newly created conference in the database.
    |--------------------------------------------------------------------------
    |
    | This method validates the request data and creates a new conference
    | record in the `conferences` table. It returns a 201 status code upon
    | successful creation.
    |
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'                         => ['required', 'string'],
		    'abstract'                      => ['required', 'string'],
		    'conference_date'               => ['required', 'date'],
		    'submission_deadline'           => ['required', 'date'],
            'review_assignment_deadline'    => ['required', 'date'],
            'review_submission_deadline'    => ['required', 'date'],
            'review_publication_date'       => ['required', 'date'],
		    'location_id'                   => ['required', 'exists:universities,id'],
            'is_active'                     => ['required', 'integer'],
        ]);

        Conference::create([
            'title'                         => $validated['title'],
		    'abstract'                      => $validated['abstract'],
		    'conference_date'               => $validated['conference_date'],
		    'submission_deadline'           => $validated['submission_deadline'],
            'review_assignment_deadline'    => $validated['review_assignment_deadline'],
            'review_submission_deadline'    => $validated['review_submission_deadline'],
            'review_publication_date'       => $validated['review_publication_date'],
		    'location_id'                   => $validated['location_id'],
            'is_active'                     => $validated['is_active'],
        ]);

        return response()->json([
            'message' => 'Successfully stored.'
        ], 201); // 201 CREATED
    }

    /*
    |--------------------------------------------------------------------------
    | Update the specified conference in the database.
    |--------------------------------------------------------------------------
    |
    | This method validates the request data, finds the specified conference
    | by its ID, and updates its information. It returns a 200 status code
    | upon successful update.
    |
    */

    public function update(Request $request, $conference_id)
    {
        $validated = $request->validate([
            'title'                         => ['required', 'string'],
		    'abstract'                      => ['required', 'string'],
		    'conference_date'               => ['required', 'date'],
		    'submission_deadline'           => ['required', 'date'],
            'review_assignment_deadline'    => ['required', 'date'],
            'review_submission_deadline'    => ['required', 'date'],
            'review_publication_date'       => ['required', 'date'],
		    'location_id'                   => ['required', 'exists:universities,id'],
            'is_active'                     => ['required', 'integer'],
        ]);

        $conference = Conference::findOrFail($conference_id);

        $conference->update([
            'title'                         => $validated['title'],
		    'abstract'                      => $validated['abstract'],
		    'conference_date'               => $validated['conference_date'],
		    'submission_deadline'           => $validated['submission_deadline'],
            'review_assignment_deadline'    => $validated['review_assignment_deadline'],
            'review_submission_deadline'    => $validated['review_submission_deadline'],
            'review_publication_date'       => $validated['review_publication_date'],
		    'location_id'                   => $validated['location_id'],
            'is_active'                     => $validated['is_active'],
        ]);

        return response()->json([
            'message' => 'Successfully updated.'
        ], 200); // 200 OK
    }

    /*
    |--------------------------------------------------------------------------
    | Remove the specified conference from the database.
    |--------------------------------------------------------------------------
    |
    | This method deletes the specified conference record based on the provided ID.
    | It returns a 200 status code upon successful deletion.
    |
    */

    public function destroy($conference_id)
    {
        $conference = Conference::findOrFail($conference_id);
        $conference->delete();

        return response()->json([
            'message' => 'Successfully deleted.'
        ], 200); // 200 OK
    }
}
