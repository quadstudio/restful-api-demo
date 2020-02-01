<?php

namespace App\Http\Controllers;

use App\Exceptions\AppModelNotFoundException;
use App\Exceptions\ForbiddenException;
use App\Http\Requests\AuthorRequest;
use App\Http\Resources\AuthorNewsResource;
use App\Http\Resources\AuthorResource;
use App\Repositories\AuthorRepository;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;

class AuthorController extends Controller
{

	/**
	 * @var AuthorRepository
	 */
	private $authors;

	/**
	 * NewsController constructor.
	 *
	 * @param AuthorRepository $authors
	 */
	public function __construct(AuthorRepository $authors)
	{

		$this->authors = $authors;
	}

	/**
	 * Display the Author resource.
	 *
	 * @param mixed $author_id
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * @throws AppModelNotFoundException
	 */
	public function show($author_id)
	{
		try {
			$author = $this->authors->find($author_id);

			return response()->json(
				AuthorResource::make($author),
				Response::HTTP_OK,
				['Content-Type' => 'application/vnd.api+json']
			);
		} catch (ModelNotFoundException $exception) {
			throw new AppModelNotFoundException(json_encode([
				'title' => trans('exception.author.not_found.title'),
				'detail' => trans(
					'exception.author.not_found.detail',
					['id' => $author_id]
				),
			]));
		}
	}

	/**
	 * Store a newly created Author resource in storage.
	 *
	 * @param  \App\Http\Requests\AuthorRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(AuthorRequest $request)
	{

		/** @var User $author */
		$author = $this->authors->store($request->input('data.attributes'));

		return response()->json(
			AuthorResource::make($author),
			Response::HTTP_CREATED,
			[
				'Content-Type' => 'application/vnd.api+json',
				'Location' => url("/authors/{$author->getKey()}"),
			]
		);
	}

	/**
	 * Update the specified Author resource in storage.
	 *
	 * @param  \App\Http\Requests\AuthorRequest $request
	 * @param mixed $author_id
	 *
	 * @return \Illuminate\Http\Response
	 * @throws AppModelNotFoundException
	 * @throws ForbiddenException
	 */
	public function update(AuthorRequest $request, $author_id)
	{
		try {
			if ($request->user()->getKey() != $author_id) {
				throw new ForbiddenException();
			}
			$author = $this->authors->update($author_id, $request->input('data.attributes'));

			return response()->json(
				AuthorResource::make($author),
				Response::HTTP_OK,
				['Content-Type' => 'application/vnd.api+json']
			);
		} catch (ModelNotFoundException $exception) {
			throw new AppModelNotFoundException(json_encode([
				'title' => trans('exception.author.not_found.title'),
				'detail' => trans(
					'exception.author.not_found.detail',
					['id' => $author_id]
				),
			]));
		}
	}

	/**
	 * Display the Author News resource.
	 *
	 * @param mixed $author_id
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * @throws AppModelNotFoundException
	 */
	public function news($author_id)
	{
		try {
			$author = $this->authors->find($author_id);

			return response()->json(
				AuthorNewsResource::make($author),
				Response::HTTP_OK,
				['Content-Type' => 'application/vnd.api+json']
			);
		} catch (ModelNotFoundException $exception) {
			throw new AppModelNotFoundException(json_encode([
				'title' => trans('exception.author.not_found.title'),
				'detail' => trans(
					'exception.author.not_found.detail',
					['id' => $author_id]
				),
			]));
		}
	}
}
