<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UsersHasArticle
 * 
 * @property int $id
 * @property int $users_id
 * @property int $articles_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Article $article
 * @property User $user
 *
 * @package App\Models
 */
class UsersHasArticle extends Model
{
	protected $table = 'users_has_articles';

	protected $casts = [
		'users_id' => 'int',
		'articles_id' => 'int'
	];

	protected $fillable = [
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
}
