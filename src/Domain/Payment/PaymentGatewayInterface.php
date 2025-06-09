<?php

namespace App\Domain\Payment;

use DateTimeImmutable;

interface PaymentGatewayInterface
{
    public function getName(): string;

    /**
     * @param Payment $payment
     * @return array<string,string|DateTimeImmutable>
     */
    public function process(Payment $payment): array;

    public function mapToUnifiedResponse(
        string $transactionId,
        \DateTimeImmutable $dateCreated
    ): array;
}
