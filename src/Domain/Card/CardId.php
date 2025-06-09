<?php

namespace App\Domain\Card;

use Symfony\Component\Uid\UuidV7;

final class CardId extends UuidV7
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
