<?php

namespace App\Controller\Checkout\Dto;

use Symfony\Component\Validator\Constraints as Assert;

use App\Validators\Constraints as AppAssert;
#[AppAssert\CardNotExpired]
class CardDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Luhn(message: 'Invalid credit card number.')]
        public string $cardNumber,

        #[Assert\NotBlank]
        #[Assert\Range(min: 1, max: 12)]
        public int    $expMonth,

        #[Assert\NotBlank]
        #[Assert\Range(min: 2000, max: 2035)]
        public int    $expYear,

        #[Assert\NotBlank]
        #[Assert\Length(min: 3, max: 4)]
        #[Assert\Type('numeric')]
        public int $cvv,
    ){}
}
