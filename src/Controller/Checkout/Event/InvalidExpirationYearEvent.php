<?php

namespace App\Controller\Checkout\Event;

use App\Http\Response\AbstractResponseEvent;

final readonly class InvalidExpirationYearEvent extends AbstractResponseEvent
{
    public const MESSAGE = 'Expiration year should be between 2000 and 2035';

    public const CODE  = 400;
    public static function fromValue($value): self
    {
        return new self([
            'expYear' =>  $value
        ]);
    }
}
