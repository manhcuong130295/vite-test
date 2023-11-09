<?php

namespace App\Transformers;

use Illuminate\Http\Request;

class ErrorResource extends Resource
{
    /**
     * ErrorResource constructor.
     *
     * @param int    $code
     * @param string $message
     * @param null   $errors
     * @param null   $resource
     */
    public function __construct(int $code, string $message = 'Bad request', $errors = null, $resource = null)
    {
        parent::__construct($resource, new MetaResource($code, $message, $errors));
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
