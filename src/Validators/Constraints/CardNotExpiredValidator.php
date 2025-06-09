<?php

namespace App\Validators\Constraints;

use App\Controller\Checkout\Dto\CardDto;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CardNotExpiredValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof CardDto) {
            return;
        }

        $currentYear = (int)date('Y');
        $currentMonth = (int)date('m');

        $isExpired = $value->expYear < $currentYear ||
            ($value->expYear == $currentYear && $value->expMonth < $currentMonth);

        if ($isExpired) {
            $this->context->buildViolation($constraint->message)
                ->atPath('expMonth')
                ->addViolation();
        }
    }
}
