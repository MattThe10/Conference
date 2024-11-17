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
}
