<?php

namespace App\Filters;


use Illuminate\Database\Eloquent\Builder;
use App\Contracts\FilterInterface;

abstract class Filter implements FilterInterface
{

	/**
	 * @var array
	 */
	protected $params;

	protected $handler;


	/**
	 * Filter constructor.
	 *
	 * @param array|null $params
	 */
	public function __construct(array $params = [])
	{
		$this->params = $params;
		$this->boot();
	}


	public function boot()
	{
		//

	}

	/**
	 * @return bool
	 */
	public function tracked()
	{
		return false;
	}

	final public function handle(Builder $builder, \Closure $next)
	{

		if (!$this->authorize()) {
			return $next($builder);
		}

		$this->before($builder);

		$this->apply($builder);


		return $next($builder);

	}

	/**
	 * Determine if the filter should be granted for the current user.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	public function before(Builder $builder)
	{

	}

}