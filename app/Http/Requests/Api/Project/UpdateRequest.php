<?php

namespace App\Http\Requests\Api\Project;

use App\Http\Requests\Api\ApiRequest;
use Illuminate\Validation\ConditionalRules;
use Illuminate\Validation\Rule;

/**
 * @property string $project_id
 * @property string $content
 */
class UpdateRequest extends ApiRequest
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
            'contents' => [
                'array',
                'nullable',
                Rule::requiredIf(function () {
                    return $this->input('inputFiles') === null && $this->input('link_contents') === null;
                })
            ],
            'link_contents' => [
                'array',
                'nullable',
                Rule::requiredIf(function () {
                    return $this->input('inputFiles') === null && $this->input('contents') === null;
                })
            ],
            'inputFiles' => [
                'array',
                'nullable',
                Rule::requiredIf(function () {
                    return $this->input('contents') === null && $this->input('link_contents') === null;
                })
            ],
            'totalDetectChars' => [
                'integer',
                $this->validateContentLength($this->input('subscription_plan_id'))
            ]
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name' => trans('message.projects.name'),
            'contents' => trans('message.projects.contents'),
            'inputFiles' => trans('message.projects.input_files'),
            'link_contents' => trans('message.projects.link_contents'),
            'totalDetectChars' => trans('message.projects.total_detectChars')
        ];
    }

        /**
     * Validate max length of content follow with current subcription plan
     * 
     * @param int $subscriptionPlanId
     * @return ConditionalRules
     */
    private function validateContentLength(int $subcriptionPlanId): ConditionalRules
    {
        switch ($subcriptionPlanId) {
            case 2:
                $result = [
                    'plan' => 2,
                    'rule'=> 'max:400000'
                ]; // Change max length content if subcription plan is standard.
                break;
            case 3:
                $result = [
                    'plan' => 3, 
                    'rule' => 'max:1000000'
                ]; // Change max length content if subcription plan is premium.
                break;
            default:
                $result = [
                    'plan' => 1, 
                    'rule' => 'max:100000'
                ]; // Default max length content if subcription plan is free.
        }

        return Rule::when($result['plan'], $result['rule'], []);
    }
}
