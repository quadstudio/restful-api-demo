<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Class NewsCollection
 * @package App\Http\Resources
 */
class NewsPaginateCollection extends ResourceCollection
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
			'meta' => [
				'page' => [
					'total' => $this->total(),
					'current' => $this->currentPage(),
					'perPage' => $this->perPage(),
				],
			],
			'links' => [
				'first' => ($resource = $this->resource->toArray())['first_page_url'],
				'last' => $resource['last_page_url'],
				'prev' => $this->previousPageUrl(),
				'next' => $this->nextPageUrl(),
			],
			'data' => NewsResource::collection($this->collection),
		];
	}
}
