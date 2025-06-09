<?php

namespace App\Infrastructure\Services;

use App\Domain\Payment\Payment;
use App\Domain\Payment\PaymentAci;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

class AciPaymentGateway implements PaymentAci
{
    private const BASE_URL = 'https://eu-test.oppwa.com/v1/payments';
    private const PAYMENT_BRAND = 'VISA';

    public function __construct(
        #[Autowire('%env(ACI_API_KEY)%')]
        private readonly string              $authKey,
        private readonly HttpClientInterface $httpClient
    )
    {
    }


    private function buildRequestData(Payment $payment): array
    {
        return [
            'entityId' => '8ac7a4c79394bdc801939736f17e063d',
            'amount' => $payment->getAmount() / 100, // the money came with cents
            'currency' => $payment->getCurrency(),
            'paymentBrand' => self::PAYMENT_BRAND,
            'paymentType' => 'DB', // Debit payment
            'card.number' => $payment->getCard()->getCardNumber(),
            'card.holder' => 'Jane Jones',
            'card.expiryMonth' => $this->formatToTwoDigits($payment->getCard()->getExpiryMonth()),
            'card.expiryYear' => $payment->getCard()->getExpiryYear(),
            'card.cvv' => $payment->getCard()->getCvv(),
        ];
    }

    private function isSuccessfulResponse(array $responseData): bool
    {
        if (!isset($responseData['result']['code'])) {
            return false;
        }

        $resultCode = $responseData['result']['code'];

        return preg_match('/^(000\.000\.|000\.100\.)/', $resultCode) === 1;
    }


    public function getName(): string
    {
        return 'aci';
    }

    public function process(Payment $payment): array
    {


        try {
            $requestData = $this->buildRequestData($payment);

            $response = $this->httpClient->request('POST', self::BASE_URL, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->authKey,
                ],
                'body' => $requestData,
            ]);

            $responseData = $response->toArray();

            if (!$this->isSuccessfulResponse($responseData)) {
                throw new \RuntimeException('ACI Payment failed: ' . ($responseData['result']['description'] ?? 'Unknown error'));
            }

            return $this->mapToUnifiedResponse($responseData['id'], new \DateTimeImmutable($responseData['timestamp']));

        } catch (TransportExceptionInterface $e) {
            throw new \RuntimeException('Network error while processing ACI payment: ' . $e->getMessage());
        } catch (ClientExceptionInterface|ServerExceptionInterface $e) {
            throw new \RuntimeException('HTTP error while processing ACI payment: ' . $e->getMessage());
        } catch (\DateMalformedStringException $e) {
            throw new \RuntimeException(' Date Male formed  ' . $e->getMessage());
        }
    }

    /**
     * @param string $transactionId
     * @param \DateTimeImmutable $dateCreated
     * @return array<string,string|\DateTimeImmutable>
     * @throws \DateMalformedStringException
     */
    public function mapToUnifiedResponse(
        string             $transactionId,
        \DateTimeImmutable $dateCreated
    ): array
    {
        return [
            'transactionId' => $transactionId,
            'dateCreated' => $dateCreated,
        ];
    }


    /** @param int $number The number to convert.
     * @return string The two-digit string representation of the number.
     */
    function formatToTwoDigits(int $number): string
    {
        return str_pad($number, 2, '0', STR_PAD_LEFT);
    }
}