<?php

namespace App\Contracts;


use Illuminate\Database\Eloquent\Builder;

interface FilterInterface
{

	/**
	 * Determine if the filter should be granted for the current user.
	 *
	 * @return boolean
	 */
	public function authorize();

	/**
	 * @param Builder $builder
	 *
	 */
	public function apply(Builder $builder);

	/**
	 * Boot filter loader
	 */
	public function boot();

}