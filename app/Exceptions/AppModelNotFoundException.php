<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class AppModelNotFoundException extends Exception
{

	/**
	 * Render the exception as an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function render($request)
	{
		return response()->json([
			'errors' => [
				[
					'code' => Response::HTTP_NOT_FOUND,
					'title' => ($message = json_decode($this->getMessage(), true))['title'],
					'detail' => $message['detail'],
				],
			],
		], Response::HTTP_NOT_FOUND);
	}
}
