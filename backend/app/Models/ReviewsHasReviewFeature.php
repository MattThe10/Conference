<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ReviewsHasReviewFeature
 * 
 * @property int $id
 * @property int $reviews_id
 * @property int $review_features_id
 * @property int|null $rating
 * @property int|null $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property ReviewFeature $review_feature
 * @property Review $review
 *
 * @package App\Models
 */
class ReviewsHasReviewFeature extends Model
{
	protected $table = 'reviews_has_review_features';

	protected $casts = [
		'reviews_id' => 'int',
		'review_features_id' => 'int',
		'rating' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'reviews_id',
		'review_features_id',
		'rating',
		'status'
	];

	public function review_feature()
	{
		return $this->belongsTo(ReviewFeature::class, 'review_features_id');
	}

	public function review()
	{
		return $this->belongsTo(Review::class, 'reviews_id');
	}
}
