<?php

namespace App\Controller\Checkout;

use App\Domain\Payment\PaymentFailException;
use App\Domain\Payment\UseCases\Interactor;
use App\Http\Request\RequestDecodeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;

final class CheckoutController extends AbstractController
{

    public function __construct(
        private RequestDecoder $decoder,
        private ResponseEncoder $encoder,
        private Interactor $interactor,
    ){}
    #[OA\Tag(name: 'Checkout')]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['card_number', 'exp_month', 'exp_year', 'cvv', 'amount', 'currency'],
            properties: [
                new OA\Property(
                    property: 'card_number',
                    description: 'Card number',
                    type: 'string',
                    example: '4242424242424242'
                ),
                new OA\Property(
                    property: 'card_exp_month',
                    description: 'Expiration month',
                    type: 'string',
                    example: '01'
                ),
                new OA\Property(
                    property: 'card_exp_year',
                    description: 'Expiration year',
                    type: 'string',
                    example: '2026'
                ),
                new OA\Property(
                    property: 'card_cvv',
                    description: 'Card CVV',
                    type: 'integer',
                    example: 463
                ),
                new OA\Property(
                    property: 'amount',
                    description: 'Payment amount',
                    type: 'integer',
                    example: 4000
                ),
                new OA\Property(
                    property: 'currency',
                    description: 'Currency code',
                    type: 'string',
                    example: 'EUR'
                ),
            ],
            type: 'object'
        )
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Business logic was executed',
        content: new OA\JsonContent(ref: new Model(type: Dto\CheckoutResponse::class)),
    )]
    #[OA\Parameter(
        name: 'getaway',
        description: 'Payment getaway provider',
        in: 'path',
        required: true,
        schema: new OA\Schema(
            type: 'string',
            default: 'shift4'
        )
    )]
    #[Route('/v1/checkout/{getaway}', name: 'app_checkout_v1', methods: 'POST')]
    public function checkoutV1(Request $request, string $getaway): JsonResponse
    {
        try {
            $request = $this->decoder->decode($request);
        }catch (RequestDecodeException $exception){
            return $this->encoder->encodeException($exception);
        }

        try {
            $payment = $this->interactor->chargePayment(
                cardNumber: $request->card->cardNumber,
                expMonth: $request->card->expMonth,
                expYear: $request->card->expYear,
                cvvNumber: $request->card->cvv,
                amount: $request->amount,
                currency: $request->currency,
                getaway: $getaway
            );
        }catch (RequestDecodeException|PaymentFailException $exception){
            return $this->encoder->encodeException($exception);
        }

        return $this->encoder->encode($payment);
    }
}
