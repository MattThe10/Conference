<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    public function index()
    {
        $faculties = Faculty::all();

        return $faculties;
    }

    public function show($id)
    {
        $faculty = Faculty::where('id', $id)->first();

        return $faculty;
    }
}
