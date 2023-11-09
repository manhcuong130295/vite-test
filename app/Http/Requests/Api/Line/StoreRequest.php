<?php

namespace App\Http\Requests\Api\Line;

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
            'provider_name' => 'required|string|max:255',
            'provider_description' => 'required|string',
            'provider_icon' => 'required_if:provider_id,null|image|nullable'
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'provider_name' => trans('message.line.provider_name'),
            'provider_description' => trans('message.line.provider_description'),
            'provider_icon' => trans('message.line.provider_icon')
        ];
    }
}
