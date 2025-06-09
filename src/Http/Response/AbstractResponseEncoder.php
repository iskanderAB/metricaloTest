<?php

namespace App\Http\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractResponseEncoder
{

    protected function createResponse(string $responseClass, array $events = [], $payload = null): JsonResponse
    {
        return new JsonResponse(new $responseClass(
            events: $events,
            payload: $payload,
        ));
    }
}
