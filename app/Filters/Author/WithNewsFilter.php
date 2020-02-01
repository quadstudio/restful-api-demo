<?php

namespace App\Filters\Author;


use App\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class WithNewsFilter extends Filter
{

	/**
	 * @param Builder $builder
	 */
	function apply(Builder $builder)
	{
		$builder->with(['news' => function ($query) {
			/** @var Builder $query */
			$query
				->select('id', 'title', 'body', 'annotation', 'published_at')
				->with(['image' => function($query){
					/** @var Builder $query */
					$query
						->select('id', 'path', 'height', 'width', 'storage');
				}])
			;
		}]);
	}
}