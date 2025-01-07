<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Review
 * 
 * @property int $id
 * @property string $comment
 * @property string|null $pro
 * @property string|null $con
 * @property int $users_id
 * @property int $articles_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Article $article
 * @property User $user
 * @property Collection|ReviewFeature[] $review_features
 *
 * @package App\Models
 */
class Review extends Model
{
	protected $table = 'reviews';

	protected $casts = [
		'users_id' => 'int',
		'articles_id' => 'int'
	];

	protected $fillable = [
		'comment',
		'pro',
		'con',
		'users_id',
		'articles_id'
	];

	public function article()
	{
		return $this->belongsTo(Article::class, 'articles_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'users_id');
	}

	public function review_features()
	{
		return $this->belongsToMany(ReviewFeature::class, 'reviews_has_review_features', 'reviews_id', 'review_features_id')
					->withPivot('id', 'rating', 'status')
					->withTimestamps();
	}

	public function index(Request $request) 
	{
		// Default number of records per page 
		$per_page = 10; 
		
		// Initialize the query with a relationship to 'university' 
		$reviews = Review::query();
		//	->with(['university']); 
			
		//Check if there a 'search' parameter in the request 
		if ($request->has('search') && $request->search !=	 null) {
		
			// Convert the search term to lowercase for case-insensitive matching 
			$search = strtolower($request->search); 
			
			// Apply filtering based on the search term 
			$reviews = $reviews->where(function ($query) use ($search) {
				$query->WhereHas('article', function ($query) use ($search) {
						$query->whereRaw('LOWER(name) LIKE ?', ['%' . $search . '%']); // Filter by related article name
					})					
					->orWhereHas('user', function ($query) use ($search) {
						$query->whereRaw('LOWER(name) LIKE ?', ['%' . $search . '%']); // Filter by related user name
						$query->whereRaw('LOWER(surname) LIKE ?', ['%' . $search . '%']); // Filter by related user surname
					});
			});
		}	
					
		//Determine whether to paginate or return all records 
		if ($request->has('search') || $request->has('page')) { 
			// Paginate results if 'search' or 'page' is present in the request 
			$reviews = $reviews->paginate($per_page); 
		} else { 
			//Return all results if neither 'search' nor 'page' is specified 
			$reviews = $reviews->get(); 
		}
		
		// Return the resulting reviews as a JSON response 
		return response()->json($reviews);
	}
}
