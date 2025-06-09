<?php

namespace App\Controller\Checkout\Event;



use App\Http\Response\AbstractResponseEvent;
use Symfony\Component\Validator\Constraints;

final readonly class InvalidCardNumberLengthEvent extends AbstractResponseEvent
{
    public const MESSAGE = 'Card number invalid';

    public const CODE  = 400;

    public static function fromLengthConstraint(int $value, Constraints\Length $constraint): self
    {
        /** @var string $email */
        $cardNumber = $value;

        return new self([
            'cardNumber' => $cardNumber,
            'min' => $constraint->min,
            'max' => $constraint->max
        ]);
    }

}
