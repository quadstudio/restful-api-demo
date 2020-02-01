<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class AuthorResource
 * @package App\Http\Resources
 */
class AuthorNewsResource extends JsonResource
{

	/**
	 * Transform the author resource into an array.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return array
	 */
	public function toArray($request)
	{
		return [
			'links' => [
				'related' => url("/authors/{$this->getkey()}"),
			],
			'data' => NewsResource::collection($this->news),
		];
	}
}
