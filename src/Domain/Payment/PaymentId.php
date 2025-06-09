<?php

namespace App\Domain\Payment;

use Symfony\Component\Uid\UuidV7;

class PaymentId extends UuidV7
{
    public function __construct(private readonly string $value){
        parent::__construct($value);
    }

    public static function new(): self
    {
        return new self(UuidV7::generate());
    }

    public function getValue(): string{
        return $this->value;
    }
}
