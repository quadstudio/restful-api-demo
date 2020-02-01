<?php

namespace App\Http\Controllers;

use App\Exceptions\AppModelNotFoundException;
use App\Http\Requests\NewsRequest;
use App\Http\Resources\NewsCollection;
use App\Http\Resources\NewsCreateResource;
use App\Http\Resources\NewsShowResource;
use App\Models\News;
use App\Repositories\NewsRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;

class NewsController extends Controller
{

	/**
	 * @var NewsRepository
	 */
	private $news;

	/**
	 * NewsController constructor.
	 *
	 * @param NewsRepository $news
	 */
	public function __construct(NewsRepository $news)
	{
		$this->news = $news;
	}

	/**
	 * Display a listing of the News resource.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index()
	{
		return response()->json(
			NewsCollection::make($this->news->getCollection()),
			Response::HTTP_OK,
			['Content-Type' => 'application/vnd.api+json']
		);
	}

	/**
	 * Store a newly created News resource in storage.
	 *
	 * @param  \App\Http\Requests\NewsRequest $request
	 *
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store(NewsRequest $request)
	{

		/** @var News $news */
		$news = $this->news->createRelation($request->user()->news(), $request->input('data.attributes'));

		return response()->json(
			NewsCreateResource::make($news),
			Response::HTTP_CREATED,
			[
				'Content-Type' => 'application/vnd.api+json',
				'Location' => url("/news/{$news->getKey()}")
			]
		);
	}

	/**
	 * Display the News resource.
	 *
	 * @param mixed $news_id
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * @throws AppModelNotFoundException
	 */
	public function show($news_id)
	{
		try {
			$news = $this->news->find($news_id);

			return response()->json(
				NewsShowResource::make($news),
				Response::HTTP_OK,
				['Content-Type' => 'application/vnd.api+json']
			);
		} catch (ModelNotFoundException $exception) {
			throw new AppModelNotFoundException(json_encode([
				'title' => trans('exception.news.not_found.title'),
				'detail' => trans(
					'exception.news.not_found.detail',
					['id' => $news_id]
				),
			]));
		}
	}


	/**
	 * Update the specified News resource in storage.
	 *
	 * @param  \App\Http\Requests\NewsRequest $request
	 * @param mixed $news_id
	 *
	 * @return \Illuminate\Http\Response
	 * @throws AppModelNotFoundException
	 */
	public function update(NewsRequest $request, $news_id)
	{

		try {
			$news = $this->news->update($news_id, $request->input('data.attributes'));

			return response()->json(
				NewsShowResource::make($news),
				Response::HTTP_OK,
				['Content-Type' => 'application/vnd.api+json']
			);
		} catch (ModelNotFoundException $exception) {
			throw new AppModelNotFoundException(json_encode([
				'title' => trans('exception.news.not_found.title'),
				'detail' => trans(
					'exception.news.not_found.detail',
					['id' => $news_id]
				),
			]));
		}
	}
}
