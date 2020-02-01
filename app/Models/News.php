<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class News
 * @package App\Models
 */
class News extends Model
{

	protected $casts = [
		'author_id' => 'integer',
		'published_at' => 'date:Y-m-d',
		'title' => 'string',
		'annotation' => 'string',
		'body' => 'string',
	];

//	protected $dates = [
//		'published_at',
//	];

	/**
	 * @var int
	 */
	protected $perPage = 2;

	/**
	 * @var array
	 */
	protected $fillable = ['title', 'annotation', 'body', 'published_at'];

//	/**
//	 * @param $value
//	 */
//	public function setPublishedAtAttribute($value)
//	{
//		$this->attributes['published_at'] = $value ? Carbon::parse($value) : null;
//	}

	/**
	 * Get the News author
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function author()
	{
		return $this->belongsTo(User::class, 'author_id');
	}

	/**
	 * Get the News image.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\MorphOne
	 */
	public function image()
	{
		return $this->morphOne(Image::class, 'imageable')
			->where('storage', 'news')
			->orderByDesc('id')
			->withDefault([
				'path' => 'images/default.png',
				'storage' => 'news',
				'width' => 720,
				'height' => 405,
			]);
	}
}
