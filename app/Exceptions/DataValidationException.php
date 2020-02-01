<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class DataValidationException extends Exception
{

	/**
	 * Render the exception as an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return void
	 */
	public function render($request)
	{
		$errors = ['errors' => []];
		foreach (json_decode($this->getMessage(), true) as $pointer => $error) {
			$errors['errors'][] = [
				'source' => ['pointer' => str_replace('.', '/', $pointer)],
				'title' => $error[0],
			];
		}
		throw new HttpResponseException(
			response()
				->json($errors, $this->getCode())
				->withHeaders([
					'Content-Type' => 'application/vnd.api+json',
					'Accept' => 'application/vnd.api+json',
				])
		);
	}
}
