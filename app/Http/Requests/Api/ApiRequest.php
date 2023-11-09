<?php

namespace App\Http\Requests\Api;

use App\Transformers\ErrorResource;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class ApiRequest extends FormRequest
{
    /**
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            (new ErrorResource(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                trans('validation.general'),
                $validator->errors()->messages()
            ))->response()->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
