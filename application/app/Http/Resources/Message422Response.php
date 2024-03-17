<?php

namespace App\Http\Resources;

use App\Enums\MessageType;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Message422Response extends JsonResponse
{
    public function __construct(string $fieldName, string $fieldMessage)
    {
        parent::__construct([
            'type' => MessageType::failure->name,
            'message' => $fieldMessage,
            'errors' => [
                $fieldName => [
                    $fieldMessage,
                ],
            ],
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
