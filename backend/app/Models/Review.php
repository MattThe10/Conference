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
}
