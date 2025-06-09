<?php

namespace App\Controller\Checkout\Event;

use App\Http\Response\AbstractResponseEvent;

final readonly class InvalidAmountEvent extends AbstractResponseEvent
{
    public const MESSAGE = 'Amount should be positive number';

    public const CODE  = 400;
    public static function fromValue($value): self
    {
        return new self([
           'amount' =>  $value
        ]);
    }
}
