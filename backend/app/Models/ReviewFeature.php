<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ReviewFeature
 * 
 * @property int $id
 * @property string $content
 * @property int $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Review[] $reviews
 *
 * @package App\Models
 */
class ReviewFeature extends Model
{
	protected $table = 'review_features';

	protected $casts = [
		'is_active' => 'int'
	];

	protected $fillable = [
		'content',
		'is_active'
	];

	public function reviews()
	{
		return $this->belongsToMany(Review::class, 'reviews_has_review_features', 'review_features_id', 'reviews_id')
					->withPivot('id', 'rating', 'status')
					->withTimestamps();
	}

	public function index(Request $request) 
	{
		// Initialize the query  
		$review_features = ReviewFeature::query()
			->with(['review']); 
			
		//Check if there a 'search' parameter in the request 
		if ($request->has('search') && $request->search != null) {
		
			// Convert the search term to lowercase for case-insensitive matching 
			$search = strtolower($request->search); 
			
			// Apply filtering based on the search term 
			$review_features = $review_features->where(function ($query) use ($search) {
				$query->whereRaw('LOWER(content) LIKE ?', ['%' . $search . '%']); // Filter by content
			});
		}	
					
		$review_features = $review_features->get(); 
				
		// Return the resulting review_features as a JSON response 
		return response()->json($review_features);
	}
}
