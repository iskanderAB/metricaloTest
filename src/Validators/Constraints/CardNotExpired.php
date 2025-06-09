<?php

namespace App\Validators\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_CLASS)]
class CardNotExpired extends Constraint
{
    public string $message = 'The credit card is expired.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
