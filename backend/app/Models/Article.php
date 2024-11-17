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
}
