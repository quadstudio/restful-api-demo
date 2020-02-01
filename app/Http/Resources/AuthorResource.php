<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class AuthorResource
 * @package App\Http\Resources
 */
class AuthorResource extends JsonResource
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
			'data' => [
				'type' => 'authors',
				'id' => $this->getKey(),
				'attributes' => [
					'name' => $this->getAttribute('name'),
				],
				"links" => [
					"self" => url("/authors/{$this->getkey()}"),
				],
			],
		];
	}
}
