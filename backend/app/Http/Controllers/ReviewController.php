<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReviewFeature;
use App\Models\ReviewsHasReviewFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function index(Request $request) 
    {
        // Initialize the query with a relationship to 'university' 
        $reviews = Review::query()
            ->with(['article', 'user', 'review_features']); 
            
        //Check if there a 'search' parameter in the request 
        if ($request->has('search') && $request->search !=   null) {
        
            // Convert the search term to lowercase for case-insensitive matching 
            $search = strtolower($request->search); 
            
            // Apply filtering based on the search term 
            $reviews = $reviews->where(function ($query) use ($search) {
                $query->WhereHas('article', function ($query) use ($search) {
                        $query->whereRaw('LOWER(title) LIKE ?', ['%' . $search . '%']); // Filter by related article name
                    })                  
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where(function ($query) use ($search) {
                            $query->whereRaw('LOWER(name) LIKE ?', ['%' . $search . '%']) // Filter by related user name
                                ->orWhereRaw('LOWER(surname) LIKE ?', ['%' . $search . '%']); // Filter by related user surname
                        });
                    });
            });
        }   
                    
        $reviews = $reviews->get(); 
                
        // Return the resulting reviews as a JSON response 
        return response()->json($reviews);
    }


    public function show($review_id)
    {
        $review = Review::with(['user', 'article', 'review_features'])->findOrFail($review_id);

        return response()->json($review);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'comment'       => ['nullable', 'string', 'max:1000'],
            'pro'           => ['nullable', 'string', 'max:1000'],
            'con'           => ['nullable', 'string', 'max:1000'],
            'user_id'       => ['required', 'integer', 'exists:users,id'],
            'article_id'    => ['required', 'integer', 'exists:articles,id'],
        ]);

        Review::create([
            'comment'       => $validated['comment'] ?? null,
            'pro'           => $validated['pro'] ?? null,
            'con'           => $validated['con'] ?? null,
            'users_id'      => $validated['user_id'],
            'articles_id'   => $validated['article_id'],
        ]);

        return response()->json([
            'message'   => 'Review created successfully.',
        ], 201);
    }

    public function update(Request $request, $review_id)
    {
        $validated = $request->validate([
            'comment'       => ['nullable', 'string', 'max:1000'],
            'pro'           => ['nullable', 'string', 'max:1000'],
            'con'           => ['nullable', 'string', 'max:1000'],
            'user_id'       => ['nullable', 'integer', 'exists:users,id'],
            'article_id'    => ['nullable', 'integer', 'exists:articles,id'],
            'features'      => ['array'],
        ]);

        $review = Review::findOrFail($review_id);
        $review->update([
            'comment'       => $validated['comment'] ?? null,
            'pro'           => $validated['pro'] ?? null,
            'con'           => $validated['con'] ?? null,
            'users_id'      => $validated['user_id'] ?? $review->users_id,
            'articles_id'   => $validated['article_id'] ?? $review->articles_id,
        ]);

        foreach ($validated['features'] as $feature_id => $value) {
            if (ReviewFeature::where('id', $feature_id)->first()->rating_type == 0) {
                ReviewsHasReviewFeature::updateOrCreate(
                    [
                        'reviews_id'   => $review->id,
                        'review_features_id'  => $feature_id,
                    ],
                    [
                        'status'       => $value,
                    ]
                );
            } else {
                ReviewsHasReviewFeature::updateOrCreate(
                    [
                        'reviews_id'   => $review->id,
                        'review_features_id'  => $feature_id,
                    ],
                    [
                        'rating'       => $value,
                    ]
                );
            }
        }

        return response()->json([
            'message'   => 'Review updated successfully.',
        ], 200);
    }

    public function destroy($review_id)
    {
        $review = Review::findOrFail($review_id);
        $review->delete();

        return response()->json([
            'message' => 'Review deleted successfully.',
        ], 200);
    }
}
