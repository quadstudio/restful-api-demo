<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class ForbiddenException extends Exception
{

	/**
	 * Render the exception as an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function render($request)
	{
		return response()->json(
			[
				'errors' => [
					[
						'code' => Response::HTTP_FORBIDDEN,
						'title' => trans('exception.forbidden.title'),
						'detail' => trans('exception.forbidden.detail'),
					],
				],
			],
			Response::HTTP_FORBIDDEN,
			[
				'Content-Type' => 'application/vnd.api+json',
			]
		);
	}

}
