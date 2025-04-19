<?php

namespace App\Payloads\Base\Enums;

use Symfony\Component\HttpFoundation\Response;

enum Status: int
{
    // 2xx Success
    case OK = Response::HTTP_OK; // 200
    case CREATED = Response::HTTP_CREATED; // 201
    case ACCEPTED = Response::HTTP_ACCEPTED; // 202
    case NO_CONTENT = Response::HTTP_NO_CONTENT; // 204

    // 4xx Client Errors
    case BAD_REQUEST = Response::HTTP_BAD_REQUEST; // 400
    case UNAUTHORIZED = Response::HTTP_UNAUTHORIZED; // 401
    case FORBIDDEN = Response::HTTP_FORBIDDEN; // 403
    case NOT_FOUND = Response::HTTP_NOT_FOUND; // 404
    case METHOD_NOT_ALLOWED = Response::HTTP_METHOD_NOT_ALLOWED; // 405
    case UNPROCESSABLE_ENTITY = Response::HTTP_UNPROCESSABLE_ENTITY; // 422

    // 5xx Server Errors
    case INTERNAL_SERVER_ERROR = Response::HTTP_INTERNAL_SERVER_ERROR; // 500
    case SERVICE_UNAVAILABLE = Response::HTTP_SERVICE_UNAVAILABLE; // 503
}
