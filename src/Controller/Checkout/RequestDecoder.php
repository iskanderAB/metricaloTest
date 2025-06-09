<?php

namespace App\Controller\Checkout;

use App\Controller\Checkout\Dto\CardDto;
use App\Controller\Checkout\Dto\CheckoutRequestDtoV1;
use App\Http\Request\HttpSource;
use App\Http\Request\RequestDecodeException;
use App\Http\Response\AbstractResponseEvent;
use App\Validators\Constraints\CardNotExpired;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RequestDecoder
{
    public function __construct(
        private ValidatorInterface $validator,
    ){}
    public function decode(Request $request): CheckoutRequestDtoV1
    {
        $checkoutRequest = new CheckoutRequestDtoV1(
            amount: HttpSource::fromBody($request, 'amount'),
            currency: HttpSource::fromBody($request, 'currency'),
            card: new CardDto(
                cardNumber: HttpSource::fromBody($request, 'card_number'),
                expMonth: HttpSource::fromBody($request, 'card_exp_month'),
                expYear: HttpSource::fromBody($request, 'card_exp_year'),
                cvv: HttpSource::fromBody($request, 'card_cvv'),
            ),
            getaway: HttpSource::fromAttributes($request, 'getaway'),
        );


        foreach ($this->validator->validate($checkoutRequest) as $violation) {
            $event = $this->mapViolationToEvent($violation->getPropertyPath(), $violation->getInvalidValue(), $violation->getConstraint());
            $errors[$event::CODE] = $event;
        }

        if (empty($errors) === false) {
            throw RequestDecodeException::fromEvents(array_values($errors));
        }
        return $checkoutRequest;
    }

    protected function mapViolationToEvent(string $property, mixed $invalidValue, ?Constraint $constraint): AbstractResponseEvent
    {
        return match (true) {
            ($property === 'card.expMonth' || $property === 'card.expYear')  && $constraint instanceof CardNotExpired
            => Event\CardExpiredEvent::fromDate($invalidValue->expMonth, $invalidValue->expYear),
            $property === 'card.cardNumber' && $constraint instanceof Assert\Luhn
            => Event\InvalidCardNumberEvent::fromNumber($invalidValue),
                $property === 'card.cardNumber' && $constraint instanceof Assert\Length
            => Event\InvalidCardNumberLengthEvent::fromLengthConstraint($invalidValue, $constraint),
            $property === 'card.expMonth' && $constraint instanceof Assert\Range
            => Event\InvalidExpirationMonthEvent::fromValue($invalidValue),
            $property === 'card.expYear' && $constraint instanceof Assert\Range
            => Event\InvalidExpirationYearEvent::fromValue($invalidValue),
            $property === 'amount' && ($constraint instanceof Assert\Type || $constraint instanceof Assert\Positive)
            => Event\InvalidAmountEvent::fromValue($invalidValue),
            $property === 'currency' && $constraint instanceof Assert\Choice
            => Event\InvalidCurrencyEvent::fromValue($invalidValue),
            $property === 'card.cvv' && $constraint instanceof Assert\Length
            => Event\InvalidCvvLengthEvent::fromLengthConstraint($invalidValue, $constraint),
            $property === 'getaway'  && $constraint instanceof Assert\Choice
            => Event\InvalidGetawayEvent::fromValue($invalidValue),

            default => throw new \RuntimeException(sprintf(
                'No event mapping for violation on property "%s" with constraint "%s"',
                $property,
                $constraint ? get_class($constraint) : 'null'
            )),
        };
    }

}
