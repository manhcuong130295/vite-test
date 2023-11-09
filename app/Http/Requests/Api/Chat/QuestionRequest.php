<?php
namespace App\Http\Requests\Api\Chat;

use App\Http\Requests\Api\ApiRequest;

class QuestionRequest extends ApiRequest
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
            'project_id' => 'required|string',
            'question' => 'required|string',
            'histories' => 'array'
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'project_id' => trans('message.projects.project_id'),
            'question' => trans('message.chat.question'),
            'histories' => trans('message.chat.histories')
        ];
    }
}