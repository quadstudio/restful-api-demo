<?php

namespace App\Filters\News;


use App\Filters\SelectFilter as Filter;

class SelectShortFilter extends Filter
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
			'news.author_id',
			'news.published_at',
		];
	}
}