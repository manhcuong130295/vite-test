<?php

namespace App\Http\Requests\Api\Zalo;

use App\Http\Requests\Api\ApiRequest;

/**
 * @property string $line_id
 */
class StoreRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'oa_id' => 'required|numeric|digits_between:1,20',
            'client_id' => 'required|numeric|digits_between:1,20',
            'client_secret' => 'required',
            'access_token' => 'required',
            'refresh_token' => 'required',
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name' => trans('message.zalo.name'),
            'oa_id' => trans('message.zalo.oa_id'),
            'client_id' => trans('message.zalo.client_id'),
            'client_secret' => trans('message.zalo.client_id'),
            'access_token' => trans('message.zalo.client_id'),
            'refresh_token' => trans('message.zalo.client_id')
        ];
    }
}
