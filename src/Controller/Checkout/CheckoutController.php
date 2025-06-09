<?php

namespace App\Controller\Checkout;

use App\Domain\Payment\PaymentFailException;
use App\Domain\Payment\UseCases\Interactor;
use App\Http\Request\RequestDecodeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

final class CheckoutController extends AbstractController
{

    public function __construct(
        private RequestDecoder $decoder,
        private ResponseEncoder $encoder,
        private Interactor $interactor,
    ){}
    #[OA\Tag(name: 'User')]
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
