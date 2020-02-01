<?php

namespace App\Http\Requests;

use App\Exceptions\DataValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

class AuthorRequest extends FormRequest
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
				'data.attributes.name' => ['required', 'string', 'max:255'],
				'data.attributes.email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
				'data.attributes.password' => ['required', 'string', 'min:8', 'confirmed'],
			],
			'PATCH' => [
				'data.attributes.name' => ['required', 'string', 'max:255'],
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
			'data.attributes.name' => trans('author.fields.name'),
			'data.attributes.password' => trans('author.fields.password'),
			'data.attributes.email' => trans('author.fields.email'),
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
