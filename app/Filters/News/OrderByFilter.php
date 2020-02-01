<?php

namespace App\Filters\News;

use App\Filters\OrderByFilter as Filter;

class OrderByFilter extends Filter
{

	/**
	 * @return array
	 */
	function columns(): array
	{
		return [
			'news.published_at' => 'desc',
		];
	}
}