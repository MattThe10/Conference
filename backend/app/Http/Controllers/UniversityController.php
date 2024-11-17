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

    public function index()
    {
        $universities = University::all();

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
}
