<?php

namespace App\Http\Controllers;

use App\Models\University;
use Illuminate\Http\Request;

class UniversityController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Retrieve all universities
    |--------------------------------------------------------------------------
    |
    | This method fetches all records from the `universities` table and 
    | returns them as a JSON response.
    |
    */
	public function index(Request $request) 
	{
		// Initialize the query  
		$universities = University::query()
            ->with(['faculties']); 
			
		//Check if there a 'search' parameter in the request 
		if ($request->has('search') && $request->search !=	 null) {
		
			// Convert the search term to lowercase for case-insensitive matching 
			$search = strtolower($request->search); 
			
			// Apply filtering based on the search term 
			$universities = $universities->where(function ($query) use ($search) {
				$query->whereRaw('LOWER(name) LIKE ?', ['%' . $search . '%']) // Filter by name
					->orwhereRaw('LOWER(address) LIKE?', ['%' . $search . '%']) // Filter by address 
					->orwhereRaw('LOWER(city) LIKE ?', ['%'. $search . '%']) // Filter by city 
					->orWhereRaw('LOWER(postal_code) LIKE ?', ['%' . $search . '%']) // Filter by postal code 
					->orWhereRaw('LOWER(country) LIKE ?', ['%' . $search . '%']); // Filter by country	
			});
		}	
					
		$universities = $universities->get(); 
				
		// Return the resulting universities as a JSON response 
		return response()->json($universities);
	}
    /*
    |--------------------------------------------------------------------------
    | Retrieve a specific university by its ID
    |--------------------------------------------------------------------------
    |
    | This method fetches a university record based on the provided ID and 
    | returns it as a JSON response. If the university is not found, it
    | returns `null`.
    |
    */

    public function show($university_id)
    {
        $university = University::find($university_id);

        return response()->json($university);
    }
    /*
    |--------------------------------------------------------------------------
    | Store a newly created university in the database.
    |--------------------------------------------------------------------------
    |
    | This method validates the request data and creates a new university
    | record in the `universities` table. It returns a 201 status code upon
    | successful creation.
    |
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'address'       => ['required', 'string', 'max:255'],
		    'city'          => ['required', 'string', 'max:255'],
		    'postal_code'   => ['nullable', 'string', 'max:10'],
		    'country'       => ['required', 'string', 'max:255'],
        ]);

        University::create([
            'name'          => $validated['name'],
            'address'       => $validated['address'],
		    'city'          => $validated['city'],
		    'postal_code'   => $validated['postal_code'] ?? null,
		    'country'       => $validated['country'],
        ]);

        return response()->json([
            'message' => 'Successfully stored.'
        ], 201); // 201 CREATED
    }

    /*
    |--------------------------------------------------------------------------
    | Update the specified university in the database.
    |--------------------------------------------------------------------------
    |
    | This method validates the request data, finds the specified university
    | by its ID, and updates its information. It returns a 200 status code
    | upon successful update.
    |
    */

    public function update(Request $request, $university_id)
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'address'       => ['required', 'string', 'max:255'],
		    'city'          => ['required', 'string', 'max:255'],
		    'postal_code'   => ['nullable', 'string', 'max:10'],
		    'country'       => ['required', 'string', 'max:255'],

        ]);

        $university = University::findOrFail($university_id);

        $university->update([
            'name'          => $validated['name'],
            'address'       => $validated['address'],
		    'city'          => $validated['city'],
		    'postal_code'   => $validated['postal_code'] ?? null,
		    'country'       => $validated['country'],
        ]);

        return response()->json([
            'message' => 'Successfully updated.'
        ], 200); // 200 OK
    }

    /*
    |--------------------------------------------------------------------------
    | Remove the specified university from the database.
    |--------------------------------------------------------------------------
    |
    | This method deletes the specified university record based on the provided ID.
    | It returns a 200 status code upon successful deletion.
    |
    */

    public function destroy($university_id)
    {
        $university = University::findOrFail($university_id);
        $university->delete();

        return response()->json([
            'message' => 'Successfully deleted.'
        ], 200); // 200 OK
    }
}
