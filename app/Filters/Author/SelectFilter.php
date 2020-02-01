<?php

namespace App\Filters\Author;


use App\Filters\SelectFilter as Filter;

class SelectFilter extends Filter
{

	/**
	 * @return array
	 */
	function columns(): array
	{
		return [
			'users.id',
			'users.name',
		];
	}
}