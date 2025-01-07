<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Faculty
 * 
 * @property int $id
 * @property string $name
 * @property string|null $address
 * @property string|null $city
 * @property string|null $postal_code
 * @property string|null $country
 * @property int $universities_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property University $university
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class Faculty extends Model
{
	protected $table = 'faculties';

	protected $casts = [
		'universities_id' => 'int'
	];

	protected $fillable = [
		'name',
		'address',
		'city',
		'postal_code',
		'country',
		'universities_id'
	];

	public function university()
	{
		return $this->belongsTo(University::class, 'universities_id');
	}

	public function users()
	{
		return $this->hasMany(User::class, 'faculties_id');
	}

	public function index(Request $request) 
	{
		// Initialize the query with a relationship to 'university' 
		$faculties = Faculty::query() 
			->with(['university']); 
			
		//Check if there a 'search' parameter in the request 
		if ($request->has('search') && $request->search !=	 null) {
		
			// Convert the search term to lowercase for case-insensitive matching 
			$search = strtolower($request->search); 
			
			// Apply filtering based on the search term 
			$faculties = $faculties->where(function ($query) use ($search) {
				$query->whereRaw('LOWER(name) LIKE ?', ['%' . $search . '%']) // Filter by name
					->orwhereRaw('LOWER(address) LIKE?', ['%' . $search . '%']) // Filter by address 
					->orwhereRaw('LOWER(city) LIKE ?', ['%'. $search . '%']) // Filter by city 
					->orWhereRaw('LOWER(postal_code) LIKE ?', ['%' . $search . '%']) // Filter by postal code 
					->orWhereRaw('LOWER(country) LIKE ?', ['%' . $search . '%']) // Filter by country
					->orWhereHas('university', function ($query) use ($search) {
						$query->whereRaw('LOWER(name) LIKE ?', ['%' . $search . '%']); // Filter by related university name
					});	
			});
		}	
					
		$faculties = $faculties->get(); 
				
		// Return the resulting faculties as a JSON response 
		return response()->json($faculties);
	}
}
