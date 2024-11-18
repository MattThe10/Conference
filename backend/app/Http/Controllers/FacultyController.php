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
        $faculty = Faculty::find($faculty_id);

        return response()->json($faculty);
    }
}
