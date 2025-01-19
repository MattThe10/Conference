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
		'conference_date' => 'datetime',
		'submission_deadline' => 'datetime',
		'review_assignment_deadline' => 'datetime',
		'review_submission_deadline' => 'datetime',
		'review_publication_date' => 'datetime',
		'is_active' => 'int',
		'location_id' => 'int'
	];

	protected $fillable = [
		'title',
		'abstract',
		'conference_date',
		'submission_deadline',
		'review_assignment_deadline',
		'review_submission_deadline',
		'review_publication_date',
		'is_active',
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
