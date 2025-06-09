<?php
declare(strict_types=1);

namespace App\Controller\Checkout\Dto;

use Symfony\Component\Validator\Constraints as Assert;


final class CheckoutRequestDtoV1
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('integer')]
        #[Assert\Positive]
        public int $amount,

        #[Assert\NotBlank]
        #[Assert\Length(min: 3, max: 3)]
        #[Assert\Choice(['USD', 'EUR'])]
        public string $currency,

        #[Assert\NotBlank]
        #[Assert\Valid]
        public CardDto $card,

        #[Assert\Choice(['shift4', 'aci'])]
        public string $getaway
    )
    {
    }
}
