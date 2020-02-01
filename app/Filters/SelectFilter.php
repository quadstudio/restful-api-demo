<?php

namespace App\Filters;


use Illuminate\Database\Eloquent\Builder;

abstract class SelectFilter extends Filter
{


	/**
	 * @param Builder $builder
	 */
	function apply(Builder $builder)
	{
		$builder->select($this->columns() ?: ['*']);
	}

	/**
	 * @return array
	 */
	abstract function columns(): array;
}