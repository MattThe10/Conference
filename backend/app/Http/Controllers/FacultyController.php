<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Retrieve all faculties
    |--------------------------------------------------------------------------
    |
    | This method fetches all records from the `faculties` table and 
    | returns them as a JSON response.
    |
    */

    public function index()
    {
        $faculties = Faculty::all();

        return response()->json($faculties);
    }

    /*
    |--------------------------------------------------------------------------
    | Retrieve a specific faculty by its ID
    |--------------------------------------------------------------------------
    |
    | This method fetches a faculty record based on the provided ID and 
    | returns it as a JSON response. If the faculty is not found, it
    | returns `null`.
    |
    */

    public function show($faculty_id)
    {
        $faculty = Faculty::findOrFail($faculty_id);

        return response()->json($faculty);
    }

    /*
    |--------------------------------------------------------------------------
    | Store a newly created faculty in the database.
    |--------------------------------------------------------------------------
    |
    | This method validates the request data and creates a new faculty
    | record in the `faculties` table. It returns a 201 status code upon
    | successful creation.
    |
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => ['required', 'string'],
            'address'       => ['nullable', 'string', 'max:255'],
            'city'          => ['nullable', 'string', 'max:255'],
            'postal_code'   => ['nullable', 'string', 'max:255'],
            'country'       => ['nullable', 'string', 'max:255'],
            'university_id' => ['required', 'exists:universities,id'],
        ]);

        Faculty::create([
            'name'              => $validated['name'],
            'address'           => $validated['address'] ?? null,
            'city'              => $validated['city'] ?? null,
            'postal_code'       => $validated['postal_code'] ?? null,
            'country'           => $validated['country'] ?? null,
            'universities_id'   => $validated['university_id'],
        ]);

        return response()->json([
            'message' => 'Successfully stored.'
        ], 201); // 201 CREATED
    }

    /*
    |--------------------------------------------------------------------------
    | Update the specified faculty in the database.
    |--------------------------------------------------------------------------
    |
    | This method validates the request data, finds the specified faculty
    | by its ID, and updates its information. It returns a 200 status code
    | upon successful update.
    |
    */

    public function update(Request $request, $faculty_id)
    {
        $validated = $request->validate([
            'name'          => ['required', 'string'],
            'address'       => ['nullable', 'string', 'max:255'],
            'city'          => ['nullable', 'string', 'max:255'],
            'postal_code'   => ['nullable', 'string', 'max:255'],
            'country'       => ['nullable', 'string', 'max:255'],
            'university_id' => ['required', 'exists:universities,id'],
        ]);

        $faculty = Faculty::findOrFail($faculty_id);

        $faculty->update([
            'name'              => $validated['name'],
            'address'           => $validated['address'] ?? null,
            'city'              => $validated['city'] ?? null,
            'postal_code'       => $validated['postal_code'] ?? null,
            'country'           => $validated['country'] ?? null,
            'universities_id'   => $validated['university_id'],
        ]);

        return response()->json([
            'message' => 'Successfully updated.'
        ], 200); // 200 OK
    }

    /*
    |--------------------------------------------------------------------------
    | Remove the specified faculty from the database.
    |--------------------------------------------------------------------------
    |
    | This method deletes the specified faculty record based on the provided ID.
    | It returns a 200 status code upon successful deletion.
    |
    */

    public function destroy($faculty_id)
    {
        $faculty = Faculty::findOrFail($faculty_id);
        $faculty->delete();

        return response()->json([
            'message' => 'Successfully deleted.'
        ], 200); // 200 OK
    }
}
