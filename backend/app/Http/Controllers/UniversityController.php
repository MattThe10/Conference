<?php

namespace App\Http\Controllers;

use App\Models\University;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    public function index()
    {
        $universities = University::all();

        return $universities;
    }

    public function show($id)
    {
        $university = University::where('id', $id)->first();

        return $university;
    }
}
