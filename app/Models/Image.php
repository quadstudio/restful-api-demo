<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Image
 * @package App\Models
 */
class Image extends Model
{

	protected $fillable = ['width', 'height', 'storage', 'path', 'imageable_type', 'imageable_id'];

	protected $casts = [
		'path' => 'string',
		'width' => 'string',
		'height' => 'string',
		'storage' => 'string',
	];

	/**
	 * Get Image model.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\MorphTo
	 */
	public function imageable()
	{
		return $this->morphTo();
	}
}
