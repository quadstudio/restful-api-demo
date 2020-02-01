<?php

namespace App\Exceptions;


use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;

class JsonApiException extends Exception
{

	public function render($request)
	{
		throw new HttpResponseException(
			response()
				->json(
					[
						'errors' => [
							[
								'code' => $this->getCode(),
								'title' => $this->getMessage(),
							],
						],
					],
					$this->getCode(),
					[
						'Content-Type' => 'application/vnd.api+json',
						'Accept' => 'application/vnd.api+json',
					]
				)
		);
	}
}