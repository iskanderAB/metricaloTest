<?php

namespace App\Controller\Checkout\Event;

use App\Http\Response\AbstractResponseEvent;

final readonly class InvalidCurrencyEvent extends AbstractResponseEvent
{
    public const MESSAGE = 'Currency should be euro or dollar';

    public const CODE  = 400;
    public static function fromValue($value): self
    {
        return new self([
            'Currency' =>  $value
        ]);
    }
}
