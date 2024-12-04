<?php

namespace App\Http\Controllers;

use App\Models\ReviewFeature;
use Illuminate\Http\Request;

class ReviewFeatureController extends Controller
{
    public function index()
    {
        $review_features = ReviewFeature::all();

        return response()->json($review_features);
    }

    public function show($review_feature_id)
    {
        $review_features = ReviewFeature::findOrFail($review_feature_id);

        return response()->json($review_features);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content'       => ['required', 'string', 'max:255'],
            'rating_type'   => ['required', 'boolean'],
            'is_active'     => ['required', 'boolean'],
        ]);

        ReviewFeature::create($validated);

        return response()->json([
            'message'   => 'Review feature created successfully.',
        ], 201);
    }

    public function update(Request $request, $review_feature_id)
    {
        $validated = $request->validate([
            'content'       => ['required', 'string', 'max:255'],
            'rating_type'   => ['required', 'integer'],
            'is_active'     => ['required', 'integer'],
        ]);

        $review_feature = ReviewFeature::findOrFail($review_feature_id);
        $review_feature->update($validated);

        return response()->json([
            'message' => 'Review feature updated successfully.',
        ], 200);
    }

    public function destroy($review_feature_id)
    {
        $review_feature = ReviewFeature::findOrFail($review_feature_id);
        $review_feature->delete();

        return response()->json([
            'message' => 'Review feature deleted successfully.',
        ], 200);
    }
}
