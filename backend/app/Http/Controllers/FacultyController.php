<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    public function index()
    {
        $faculties = Faculty::all();

        return response()->json($faculties);
    }

    public function show($faculty_id)
    {
        $faculty = Faculty::find($faculty_id);

        return response()->json($faculty);
    }
}
