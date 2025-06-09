<?php

namespace App\Domain\Payment\UseCases;

use App\Domain\Card\Card;
use App\Domain\Card\CardId;
use App\Domain\Payment\Enums\Getaway;
use App\Domain\Payment\Payment;
use App\Domain\Payment\PaymentFailException;
use App\Domain\Payment\PaymentId;
use App\Domain\Payment\Storage\PaymentRepositoryInterface;
use App\Infrastructure\Services\AciPaymentGateway;
use App\Infrastructure\Services\Shift4PaymentGateway;

readonly class Interactor
{
    public function __construct(
        private Shift4PaymentGateway $shift4PaymentGateway,
        private AciPaymentGateway $aciPaymentGateway,
        private PaymentRepositoryInterface $paymentRepository,
    ){}
    public function chargePayment(
        int $cardNumber,
        int $expMonth,
        int $expYear,
        int $cvvNumber,
        int $amount,
        string $currency,
        string $getaway

    ): Payment
    {
        $card = new Card(
            cardNumber: $cardNumber,
            expiryMonth: $expMonth,
            expiryYear: $expYear,
            cvv: $cvvNumber,
        );
        $payment = new Payment(
            id: PaymentId::new(),
            amount: $amount,
            currency: $currency,
            card: $card,
        );

        try {
            $responseContext = match ($getaway) {
                Getaway::SHIFT4->value => $this->shift4PaymentGateway->process($payment),
                Getaway::ACI->value => $this->aciPaymentGateway->process($payment),
                default => throw new \InvalidArgumentException('Invalid payment gateway'),
            };

            $payment->setTransactionId($responseContext['transactionId']);
            $payment->setCreatedDate($responseContext['dateCreated']);
            $this->paymentRepository->insert($payment);
            return $payment;
        } catch (\RuntimeException|\InvalidArgumentException $exception) {
            throw new PaymentFailException($exception->getMessage());
        }


    }
}
