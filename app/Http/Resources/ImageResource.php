<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ImageResource extends JsonResource
{

	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return array
	 */
	public function toArray($request)
	{
		return [
			'data' => [
				'type' => 'images',
				'id' => $this->getKey(),
				'attributes' => [
					'storage' => $this->getAttribute('storage'),
					'width' => $this->getAttribute('width'),
					'height' => $this->getAttribute('height'),
				],
				"links" => [
					"self" => $this->getAttribute('path'),
				],
			],
		];
	}
}
