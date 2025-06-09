<?php
declare(strict_types=1);

namespace App\Controller\Checkout\Event;

use App\Http\Response\AbstractResponseEvent;

final readonly class InvalidCardNumberEvent extends AbstractResponseEvent
{
    public const MESSAGE = 'Card number invalid';

    public const CODE  = 400;


    public static function fromNumber(string $value): self
    {
        $cardNumber = $value;

        return new self([
            'cardNumber' => $cardNumber,
        ]);
    }
}
