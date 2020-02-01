<?php

namespace App\Filters;


use Illuminate\Database\Eloquent\Builder;

abstract class OrderByFilter extends Filter
{

	/**
	 * @param Builder $builder
	 */
	function apply(Builder $builder)
	{
		if (!empty($this->columns())) {
			foreach ($this->columns() as $column => $direction) {
				$builder->orderBy($column, $direction);
			}
		}
	}

	/**
	 * @return array
	 */
	abstract function columns(): array;
}