<?php

namespace App\Http\Controllers;

use App\Models\University;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    public function index()
    {
        $universities = University::all();

        return response()->json($universities);
    }

    public function show($university_id)
    {
        $university = University::find($university_id);

        return response()->json($university);
    }
}
