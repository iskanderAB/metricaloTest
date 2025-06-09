<?php

namespace App\Domain\Payment;

use App\Domain\Card\Card;
use Symfony\Component\Validator\Constraints\Currency;

class Payment
{
    public function __construct(
        private PaymentId $id,
        private int $amount,
        private string $currency,
        private Card $card,
        private ?string $transactionId  = null,
        private ?\DateTimeImmutable $createdDate= null,
    ){}

    public function getCreatedDate(): ?\DateTimeImmutable
    {
        return $this->createdDate;
    }

    public function setCreatedDate(?\DateTimeImmutable $createdDate): void
    {
        $this->createdDate = $createdDate;
    }

    public function getId(): PaymentId
    {
        return $this->id;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCard(): Card
    {
        return $this->card;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function setTransactionId(?string $transactionId): void
    {
        $this->transactionId = $transactionId;
    }

    public function toContext(): array{
       return [
           'transactionId'=> $this->transactionId,
           'amount'=> $this->amount,
           'currency'=> $this->currency,
           'createdDate'=> $this->createdDate,
           'cardBin' => $this->card->getCardBin()
       ];

    }
}
