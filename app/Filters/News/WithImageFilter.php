<?php

namespace App\Filters\News;


use App\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class WithImageFilter extends Filter
{

	/**
	 * @param Builder $builder
	 *
	 * @return Builder
	 */
	function apply(Builder $builder)
	{

		$builder->with(['image' => function ($query) {
			/** @var Builder $query */
			$query->select('id', 'path', 'width', 'height', 'storage', 'imageable_id', 'imageable_type');
		}]);

	}
}