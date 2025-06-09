<?php

namespace App\Controller\Checkout\Event;

use App\Http\Response\AbstractResponseEvent;

final readonly class InvalidExpirationMonthEvent extends AbstractResponseEvent
{
    public const MESSAGE = 'expiration Month should be between 1 and 12';

    public const CODE  = 400;
    public static function fromValue($value): self
    {
        return new self([
            'expMonth' =>  $value
        ]);
    }
}
