<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Class NewsCollection
 * @package App\Http\Resources
 */
class NewsCollection extends ResourceCollection
{

	/**
	 * Transform the news resource collection into an array.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return array
	 */
	public function toArray($request)
	{
		return [
			'links' => [
				'self' => url("/news"),
			],
			'data' => NewsResource::collection($this->collection),
		];
	}
}
