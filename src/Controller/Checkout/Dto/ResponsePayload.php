<?php

namespace App\Controller\Checkout\Dto;

use App\Http\Response\AbstractResponseEncoder;

class ResponsePayload extends AbstractResponseEncoder
{
    public function __construct(
        public string $transactionId,
        public \DateTimeImmutable $transactionDate,
        public int $amount,
        public string $currency,
        public string $cardBin
    ){}
}
