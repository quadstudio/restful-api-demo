<?php

namespace App\Http\Requests;

use App\Exceptions\DataValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
	    return true;
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
				'data.attributes.width' => 'required|string',
				'data.attributes.height' => 'required|string',
				'data.attributes.storage' => 'required|string',
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
			'data.attributes.width' => trans('image.fields.width'),
			'data.attributes.height' => trans('image.fields.height'),
			'data.attributes.storage' => trans('image.fields.storage'),
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
