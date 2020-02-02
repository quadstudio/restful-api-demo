<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('/news', 'NewsController')->only(['index', 'show']);
Route::get('/authors/{author_id}/news', 'AuthorController@news')->name('authors.news');
Route::apiResource('/authors', 'AuthorController')->only(['show']);

Route::middleware('auth:api')->group(function () {
	Route::get('/profile', 'AuthUserController@show');
	Route::apiResource('/news', 'NewsController')->only(['store', 'update']);
	Route::apiResource('/authors', 'AuthorController')->only(['store', 'update']);
	Route::apiResource('/images', 'ImageController')->only(['store']);
});

