<?php

namespace App\Controller\Checkout\Event;

use App\Http\Response\AbstractResponseEvent;

final readonly class CardExpiredEvent extends AbstractResponseEvent
{
    public const MESSAGE = 'Card is expired.';

    public const CODE  = 400;
    public static function fromDate(int $expYear, int $expMonth): self
    {
        return new self([
            'expYear' =>  $expYear,
            'expMonth' =>  $expMonth,
        ]);
    }
}
