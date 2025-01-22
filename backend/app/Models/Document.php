<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Document
 * 
 * @property int $id
 * @property string $name
 * @property string $path
 * @property string $ext
 * @property int $articles_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Article $article
 *
 * @package App\Models
 */
class Document extends Model
{
	protected $table = 'documents';

	protected $casts = [
		'articles_id' => 'int'
	];

	protected $fillable = [
		'name',
		'path',
		'ext',
		'articles_id'
	];

	public function article()
	{
		return $this->belongsTo(Article::class, 'articles_id');
	}
}
