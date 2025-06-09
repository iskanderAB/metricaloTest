<?php

namespace App\Controller\Checkout;

use App\Domain\Payment\Payment;
use App\Domain\Payment\PaymentFailException;
use App\Http\Request\RequestDecodeException;
use App\Http\Response\AbstractResponseEncoder;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseEncoder extends AbstractResponseEncoder
{
    public function encode(Payment $payment): JsonResponse
    {
        $events = [Event\CheckoutSuccess::create()];

        return $this->createResponse(
            responseClass: Dto\CheckoutResponse::class,
            events: $events,
            payload: new Dto\ResponsePayload(
               transactionId: $payment->getTransactionId(),
               transactionDate:  $payment->getCreatedDate(),
               amount: $payment->getAmount(),
               currency: $payment->getCurrency(),
               cardBin: $payment->getCard()->getCardBin(),
            ),
        );
    }

    public function encodeException(RequestDecodeException|PaymentFailException|\RuntimeException $exception): JsonResponse
    {
        $events = match ($exception::class) {
            //we can make it better in the future :)
            PaymentFailException::class => ["exception" => $exception->getMessage()],
            RequestDecodeException::class => $exception->getEvents(),
        };

        return $this->createResponse(Dto\CheckoutResponse::class, $events);
    }
}
