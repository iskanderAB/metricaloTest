<?php

namespace App\Controller\Checkout\Event;

use App\Http\Response\AbstractResponseEvent;
use Symfony\Component\Validator\Constraints;

final readonly class InvalidCvvLengthEvent extends AbstractResponseEvent
{
    public const MESSAGE = 'CVV length should be between 3 and 4 characters';

    public const CODE  = 400;
    public static function fromLengthConstraint(int $value, Constraints\Length $constraint): self
    {
        return new self([
            'cvv' => $value,
            'min' => $constraint->min,
            'max' => $constraint->max
        ]);
    }
}
