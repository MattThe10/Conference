<?php

namespace App\Http\Controllers;

use App\Models\ReviewFeature;
use Illuminate\Http\Request;

class ReviewFeatureController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Retrieve all review_features
    |--------------------------------------------------------------------------
    |
    | This method fetches all records from the `review_features` table and 
    | returns them as a JSON response.
    |
    */

    public function index()
    {
        $review_features = review_feature::all();

        return response()->json($review_features);
    }

    /*
    |--------------------------------------------------------------------------
    | Retrieve a specific review_feature by its ID
    |--------------------------------------------------------------------------
    |
    | This method fetches a review_feature record based on the provided ID and 
    | returns it as a JSON response. If the review_feature is not found, it
    | returns `null`.
    |
    */

    public function show($review_feature_id)
    {
        $review_feature = Faculty::findOrFail($review_feature_id);

        return response()->json($review_feature);
    }

    /*
    |--------------------------------------------------------------------------
    | Store a newly created review_feature in the database.
    |--------------------------------------------------------------------------
    |
    | This method validates the request data and creates a new review_feature
    | record in the `review_features` table. It returns a 201 status code upon
    | successful creation.
    |
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content'   => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'int'],
        ]);

        review_feature::create([
            'content'   => $validated['content'],
            'is_active' => $validated['is_active'],
        ]);

        return response()->json([
            'message' => 'Successfully stored.'
        ], 201); // 201 CREATED
    }

    /*
    |--------------------------------------------------------------------------
    | Update the specified review_feature in the database.
    |--------------------------------------------------------------------------
    |
    | This method validates the request data, finds the specified review_feature
    | by its ID, and updates its information. It returns a 200 status code
    | upon successful update.
    |
    */

    public function update(Request $request, $review_feature_id)
    {
        $validated = $request->validate([
            'content'   => ['required', 'string', 'max:255'],
            'is_active'  => ['required', 'int'],
        ]);

        $review_feature = Faculty::findOrFail($review_feature_id);

        $review_feature->update([
            'content'   => $validated['content'],
            'is_active' => $validated['is_active'],
        ]);

        return response()->json([
            'message' => 'Successfully updated.'
        ], 200); // 200 OK
    }

    /*
    |--------------------------------------------------------------------------
    | Remove the specified review_feature from the database.
    |--------------------------------------------------------------------------
    |
    | This method deletes the specified review_feature record based on the provided ID.
    | It returns a 200 status code upon successful deletion.
    |
    */

    public function destroy($review_feature_id)
    {
        $review_feature = Faculty::findOrFail($review_feature_id);
        $review_feature->delete();

        return response()->json([
            'message' => 'Successfully deleted.'
        ], 200); // 200 OK
    }
}
