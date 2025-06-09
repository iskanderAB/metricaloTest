<?php

namespace App\Infrastructure\Services;

use App\Domain\Payment\Payment;
use App\Domain\Payment\PaymentShift4;
use Psr\Log\LoggerInterface;
use Shift4\Exception\Shift4Exception;
use Shift4\Request\ChargeRequest;
use Shift4\Shift4Gateway;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class Shift4PaymentGateway implements PaymentShift4
{
    public function __construct(
        #[Autowire('%env(SHIFT4_API_KEY)%')]
        private string          $apiKey,
        private LoggerInterface $logger,
    ){}

    /**
     * @param Payment $payment
     * @return array<string, \DateTimeImmutable|null>
     */
    public function process(Payment $payment): array
    {
        $gateway = new Shift4Gateway($this->apiKey);
        $request = (new ChargeRequest())
            ->amount($payment->getAmount())
            ->currency($payment->getCurrency())
            ->card([
                'number' => $payment->getCard()->getCardNumber(),
                'expMonth' => $payment->getCard()->getExpiryMonth(),
                'expYear' => $payment->getCard()->getExpiryYear(),
                'cvc' => $payment->getCard()->getCvv(),
            ]);

        try {
            $charge = $gateway->createCharge($request);
            $this->logger->info('Payment processed successfully', ['transaction_id' => $charge->getId()]);

            return $this->mapToUnifiedResponse(
                transactionId: $charge->getId(),
                dateCreated: (new \DateTimeImmutable())->setTimestamp($charge->getCreated()),
            );
        } catch (Shift4Exception $e) {
            $this->logger->error('Shift4 error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw new \RuntimeException(sprintf(
                'Shift4 error (%s %s): %s',
                $e->getType(),
                $e->getCode(),
                $e->getMessage()
            ));
        }
    }

    public function getName(): string
    {
        return 'shift4';
    }


    /**
     * @param string $transactionId
     * @param \DateTimeImmutable $dateCreated
     * @return array<string,string>
     */
    public function mapToUnifiedResponse(
        string $transactionId,
        \DateTimeImmutable $dateCreated
    ): array
    {
        return [
            'transactionId' => $transactionId,
            'dateCreated' => $dateCreated,
        ];
    }
}
