<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use Illuminate\Http\Request;

class ConferenceController extends Controller
{
    public function index()
    {
        $conferences = Conference::all();

        return response()->json($conferences);
    }

    public function show($conference_id)
    {
        $conference = Conference::find($conference_id);

        return response()->json($conference);
    }
}
