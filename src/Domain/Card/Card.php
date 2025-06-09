<?php

namespace App\Domain\Card;

class Card
{
    public function __construct(
        private string $cardNumber,
        private int $expiryMonth,
        private int $expiryYear,
        private int $cvv,
        public ?CardId $id = null,
    ){}

    public function getId(): CardId|null
    {
        return $this->id;
    }

    public function setId(CardId $id): void
    {
        $this->id = $id;
    }

    public function getCardNumber(): int
    {
        return $this->cardNumber;
    }

    public function setCardNumber(int $cardNumber): void
    {
        $this->cardNumber = $cardNumber;
    }

    public function getExpiryMonth(): int
    {
        return $this->expiryMonth;
    }

    public function setExpiryMonth(int $expiryMonth): void
    {
        $this->expiryMonth = $expiryMonth;
    }

    public function getExpiryYear(): int
    {
        return $this->expiryYear;
    }

    public function setExpiryYear(int $expiryYear): void
    {
        $this->expiryYear = $expiryYear;
    }

    public function getCvv(): int
    {
        return $this->cvv;
    }

    public function setCvv(int $cvv): void
    {
        $this->cvv = $cvv;
    }

    public function getAciId(): ?string
    {
        return $this->aciId;
    }

    public function setAciId(?string $aciId): void
    {
        $this->aciId = $aciId;
    }

    public function getCardBin(): string{
        return substr($this->cardNumber,0, 6);
    }
}
