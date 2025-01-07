<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class University
 * 
 * @property int $id
 * @property string $name
 * @property string|null $address
 * @property string|null $city
 * @property string|null $postal_code
 * @property string|null $country
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Conference[] $conferences
 * @property Collection|Faculty[] $faculties
 *
 * @package App\Models
 */
class University extends Model
{
	protected $table = 'universities';

	protected $fillable = [
		'name',
		'address',
		'city',
		'postal_code',
		'country'
	];

	public function conferences()
	{
		return $this->hasMany(Conference::class, 'location_id');
	}

	public function faculties()
	{
		return $this->hasMany(Faculty::class, 'universities_id');
	}

	public function index(Request $request) 
	{
		// Initialize the query  
		$universities = University::query(); 
			
		//Check if there a 'search' parameter in the request 
		if ($request->has('search') && $request->search !=	 null) {
		
			// Convert the search term to lowercase for case-insensitive matching 
			$search = strtolower($request->search); 
			
			// Apply filtering based on the search term 
			$universities = $universities->where(function ($query) use ($search) {
				$query->whereRaw('LOWER(name) LIKE ?', ['%' . $search . '%']) // Filter by name
					->orwhereRaw('LOWER(address) LIKE?', ['%' . $search . '%']) // Filter by address 
					->orwhereRaw('LOWER(city) LIKE ?', ['%'. $search . '%']) // Filter by city 
					->orWhereRaw('LOWER(postal_code) LIKE ?', ['%' . $search . '%']) // Filter by postal code 
					->orWhereRaw('LOWER(country) LIKE ?', ['%' . $search . '%']); // Filter by country	
			});
		}	
					
		$universities = $universities->get(); 
				
		// Return the resulting universities as a JSON response 
		return response()->json($universities);
	}

}
