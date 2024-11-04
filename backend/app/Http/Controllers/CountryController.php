<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::all();

        return $countries;
    }

    public function show($id)
    {
        $country = Country::where('id', $id)->first();

        return $country;
    }
}
