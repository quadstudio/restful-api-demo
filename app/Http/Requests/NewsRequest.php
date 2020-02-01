<?php

namespace App\Http\Requests;

use App\Exceptions\DataValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
{

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return $this->isJson();
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$rules = $this->getRules();

		return $rules[$this->method()] ?? [];
	}

	public function getRules()
	{

		return [
			'POST' => [
				'data.attributes.title' => 'required|string|max:255',
				'data.attributes.annotation' => 'required|string|max:255',
				'data.attributes.body' => 'required|string',
				'data.attributes.published_at' => 'required|date_format:"Y-m-d"',
			],
			'PATCH' => [
				'data.attributes.title' => 'required|string|max:255',
				'data.attributes.annotation' => 'required|string|max:255',
				'data.attributes.body' => 'required|string',
				'data.attributes.published_at' => 'required|date_format:"Y-m-d"',
			],

		];
	}

	/**
	 * Get custom attributes for validator errors.
	 *
	 * @return array
	 */
	public function attributes()
	{
		return [
			'data.attributes.title' => trans('news.fields.title'),
			'data.attributes.body' => trans('news.fields.body'),
			'data.attributes.annotation' => trans('news.fields.annotation'),
			'data.attributes.published_at' => trans('news.fields.published_at'),
		];
	}

	/**
	 * @param \Illuminate\Contracts\Validation\Validator $validator
	 *
	 * @throws DataValidationException
	 */
	protected function failedValidation(Validator $validator)
	{
		throw new DataValidationException(json_encode((new ValidationException($validator))->errors()), Response::HTTP_UNPROCESSABLE_ENTITY);
	}
}
