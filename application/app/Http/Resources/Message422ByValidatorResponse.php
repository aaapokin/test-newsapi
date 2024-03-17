<?php

namespace App\Http\Resources;

use App\Enums\MessageType;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

class Message422ByValidatorResponse extends JsonResponse
{
    public function __construct(Validator $validator)
    {
        parent::__construct([
            'type' => MessageType::failure->name,
            'message' => $validator->getMessageBag(),
            'errors' => $validator->errors(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
