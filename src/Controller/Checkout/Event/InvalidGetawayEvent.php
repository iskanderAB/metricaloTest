<?php

namespace App\Controller\Checkout\Event;

use App\Http\Response\AbstractResponseEvent;

final readonly class InvalidGetawayEvent extends AbstractResponseEvent
{
    public const MESSAGE = 'Getaway should be shift4 or aci';

    public const CODE  = 400;
    public static function fromValue($value): self
    {
        return new self([
            'getaway' =>  $value
        ]);
    }
}