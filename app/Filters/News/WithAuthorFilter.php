<?php

namespace App\Filters\News;


use App\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class WithAuthorFilter extends Filter
{

	/**
	 * @param Builder $builder
	 */
	function apply(Builder $builder)
	{
		$builder->with(['author' => function ($query) {
			/** @var Builder $query */
			$query->select('id', 'name');
		}]);
	}
}