<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Conference
 * 
 * @property int $id
 * @property Carbon $start_year
 * @property Carbon $end_year
 * @property Carbon $conference_date
 * @property Carbon $submission_deadline
 * @property int $location_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property University $university
 * @property Collection|Article[] $articles
 *
 * @package App\Models
 */
class Conference extends Model
{
	protected $table = 'conferences';

	protected $casts = [
		'start_year' => 'datetime',
		'end_year' => 'datetime',
		'conference_date' => 'datetime',
		'submission_deadline' => 'datetime',
		'location_id' => 'int'
	];

	protected $fillable = [
		'start_year',
		'end_year',
		'conference_date',
		'submission_deadline',
		'location_id'
	];

	public function university()
	{
		return $this->belongsTo(University::class, 'location_id');
	}

	public function articles()
	{
		return $this->hasMany(Article::class, 'conferences_id');
	}
}
