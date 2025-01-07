<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Article
 * 
 * @property int $id
 * @property string $title
 * @property int $article_statuses_id
 * @property int $conferences_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property ArticleStatus $article_status
 * @property Conference $conference
 * @property Collection|Document[] $documents
 * @property Collection|Review[] $reviews
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class Article extends Model
{
	protected $table = 'articles';

	protected $casts = [
		'article_statuses_id' => 'int',
		'conferences_id' => 'int'
	];

	protected $fillable = [
		'title',
		'article_statuses_id',
		'conferences_id'
	];

	public function article_status()
	{
		return $this->belongsTo(ArticleStatus::class, 'article_statuses_id');
	}

	public function conference()
	{
		return $this->belongsTo(Conference::class, 'conferences_id');
	}

	public function documents()
	{
		return $this->hasMany(Document::class, 'articles_id');
	}

	public function reviews()
	{
		return $this->hasMany(Review::class, 'articles_id');
	}

	public function users()
	{
		return $this->belongsToMany(User::class, 'users_has_articles', 'articles_id', 'users_id')
					->withPivot('id')
					->withTimestamps();
	}

	public function index(Request $request) 
	{
		// Default number of records per page 
		$per_page = 10; 
		
		// Initialize the query  
		$articles = Article::query();
		//	->with(['conference']); 
			
		//Check if there a 'search' parameter in the request 
		if ($request->has('search') && $request->search != null) {
		
			// Convert the search term to lowercase for case-insensitive matching 
			$search = strtolower($request->search); 
			
			// Apply filtering based on the search term 
			$articles = $articles->where(function ($query) use ($search) {
				$query->whereRaw('LOWER(title) LIKE ?', ['%' . $search . '%']); // Filter by title
			});
		}	
					
		//Determine whether to paginate or return all records 
		if ($request->has('search') || $request->has('page')) { 
			// Paginate results if 'search' or 'page' is present in the request 
			$articles = $articles->paginate($per_page); 
		} else { 
			//Return all results if neither 'search' nor 'page' is specified 
			$articles = $articles->get(); 
		}
		
		// Return the resulting articles as a JSON response 
		return response()->json($articles);
	}
}
