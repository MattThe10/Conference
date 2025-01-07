<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ArticleStatus
 * 
 * @property int $id
 * @property string $key
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Article[] $articles
 *
 * @package App\Models
 */
class ArticleStatus extends Model
{
	protected $table = 'article_statuses';

	protected $fillable = [
		'key',
		'name'
	];

	public function articles()
	{
		return $this->hasMany(Article::class, 'article_statuses_id');
	}

	public function index(Request $request) 
	{
		// Default number of records per page 
		$per_page = 10; 
		
		// Initialize the query  
		$article_statuses = ArticleStatus::query(); 
			
		//Check if there a 'search' parameter in the request 
		if ($request->has('search') && $request->search !=	 null) {
		
			// Convert the search term to lowercase for case-insensitive matching 
			$search = strtolower($request->search); 
			
			// Apply filtering based on the search term 
			$article_statuses = $article_statuses->where(function ($query) use ($search) {
				$query->whereRaw('LOWER(key) LIKE ?', ['%' . $search . '%']) // Filter by key
					->orwhereRaw('LOWER(name) LIKE?', ['%' . $search . '%']); // Filter by name 
			});
		}	
					
		//Determine whether to paginate or return all records 
		if ($request->has('search') || $request->has('page')) { 
			// Paginate results if 'search' or 'page' is present in the request 
			$article_statuses = $article_statuses->paginate($per_page); 
		} else { 
			//Return all results if neither 'search' nor 'page' is specified 
			$article_statuses = $article_statuses->get(); 
		}
		
		// Return the resulting article_statuses as a JSON response 
		return response()->json($article_statuses);
	}
}
