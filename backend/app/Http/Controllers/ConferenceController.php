<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use Illuminate\Http\Request;

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

    public function index()
    {
        $conferences = conference::all();

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
        $conference = Conference::findOrFail($conference_id);

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
            'start_year'          => ['required', 'datetime'],
		    'end_year'            => ['required', 'datetime'],
		    'conference_date'     => ['required', 'datetime'],
		    'submission_deadline' => ['required', 'datetime'],
		    'location_id'         => ['required', 'exists:universities,id'],
        ]);

        conference::create([
            'start_year'          => $validated['start_year'],
		    'end_year'            => $validated['end_year'],
		    'conference_date'     => $validated['conference_date'],
		    'submission_deadline' => $validated['submission_deadline'],
		    'location_id'         => $validated['location_id'],
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
            'start_year'          => ['required', 'datetime'],
		    'end_year'            => ['required', 'datetime'],
		    'conference_date'     => ['required', 'datetime'],
		    'submission_deadline' => ['required', 'datetime'],
		    'location_id'         => ['required', 'exists:universities,id'],
        ]);

        $conference = Faculty::findOrFail($conference_id);

        $conference->update([
            'start_year'          => $validated['start_year'],
		    'end_year'            => $validated['end_year'],
		    'conference_date'     => $validated['conference_date'],
		    'submission_deadline' => $validated['submission_deadline'],
		    'location_id'         => $validated['location_id'],
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
