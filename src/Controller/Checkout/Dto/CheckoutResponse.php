<?php

namespace App\Controller\Checkout\Dto;

use App\Http\Response\AbstractResponseDto;
use App\Http\Response\AbstractResponseEvent;

readonly class CheckoutResponse extends AbstractResponseDto
{
    /**
     * @param list<AbstractResponseEvent> $events
     * @param ResponsePayload|null $payload
     */
    public function __construct(
        public array  $events,
        public ?ResponsePayload $payload = null,
    ){}
}
