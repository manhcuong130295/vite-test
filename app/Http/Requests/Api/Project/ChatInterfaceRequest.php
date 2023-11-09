<?php

namespace App\Http\Requests\Api\Project;

use App\Http\Requests\Api\ApiRequest;

/**
 * @property string $chat_interface_id
 */
class ChatInterfaceRequest extends ApiRequest
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
            'chatbot_name' => 'required|string|max:255',
            'theme_color' => 'required|string',
            'initial_message' => 'required|string|max:255',
            'chatbot_picture' => 'required_if:chat_interface_id,null|image|nullable|max:50000|dimensions:width=height',
            'chatbot_picture_active' => 'required|boolean',
            'project_id' => 'required|exists:projects,id',
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'chatbot_name' => trans('message.chat_interface.chatbot_name'),
            'initial_message' => trans('message.chat_interface.initial_message'),
            'theme_color' => trans('message.chat_interface.theme_color'),
            'chatbot_picture' => trans('message.chat_interface.chatbot_picture'),
            'chatbot_picture_active' => trans('message.chat_interface.chatbot_picture_active'),
            'project_id' => trans('message.chat_interface.project_id'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'chatbot_picture.dimensions' => trans('message.chat_interface.chatbot_picture_dimensions'),
        ];
    }


}
