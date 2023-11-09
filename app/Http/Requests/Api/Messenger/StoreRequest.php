<?php

namespace App\Http\Requests\Api\Messenger;

use App\Http\Requests\Api\ApiRequest;

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
            'page_id' => 'required|numeric|digits_between:1,15',
            'access_token' => 'required'
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name' => trans('message.messenger.name'),
            'page_id' => trans('message.messenger.page_id'),
            'access_token' => trans('message.messenger.access_token')
        ];
    }
}
