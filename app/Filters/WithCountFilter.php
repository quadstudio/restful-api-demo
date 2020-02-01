<?php

namespace App\Filters;


use Illuminate\Database\Eloquent\Builder;

abstract class WithCountFilter extends Filter
{

	/**
	 * @param Builder $builder
	 */
	function apply(Builder $builder)
	{
		if (!empty($this->relations())) {
			$builder->withCount($this->relations());
		}
	}

	/**
	 * @return array
	 */
	abstract function relations(): array;
}