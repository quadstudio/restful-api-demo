<?php

namespace App\Filters\News;


use App\Filters\SelectFilter as Filter;

class SelectFullFilter extends Filter
{

	/**
	 * @return array
	 */
	function columns(): array
	{
		return [
			'news.id',
			'news.title',
			'news.annotation',
			'news.body',
			'news.author_id',
			'news.published_at',
		];
	}
}