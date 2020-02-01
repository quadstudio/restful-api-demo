<?php

namespace App\Repositories;


use App\Models\Image;

/**
 * Class NewsRepository
 * @package App\Repositories
 */
class ImageRepository extends Repository
{

	/**
	 * Get the News Model class name.
	 *
	 * @return string
	 */
	public function model()
	{
		return Image::class;
	}

}