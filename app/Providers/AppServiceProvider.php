<?php

namespace App\Providers;

use App\Models\News;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->loadMorphMap();
	}

	/**
	 * @return void
	 */
	private function loadMorphMap()
	{
		Relation::morphMap([
			'news' => News::class,
		]);
	}
}
