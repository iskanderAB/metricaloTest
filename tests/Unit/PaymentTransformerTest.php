<?php

namespace App\Tests\Service;

use App\Domain\Payment\PaymentId;
use PHPUnit\Framework\TestCase;
use App\Service\PaymentTransformer;
use App\Domain\Payment\Payment;
use App\Entity\Payment as PaymentEntity;

class PaymentTransformerTest extends TestCase
{
    public function testToEntity()
    {
        $paymentModel = $this->createMock(Payment::class);

        $id = PaymentId::new();

        $paymentModel->method('getId')->willReturn($id);
        $paymentModel->method('getTransactionId')->willReturn('txn123');
        $paymentModel->method('getAmount')->willReturn(100);
        $paymentModel->method('getCurrency')->willReturn('USD');
        $paymentModel->method('getCreatedDate')->willReturn(new \DateTimeImmutable('2023-01-01'));


        $transformer = new PaymentTransformer();

        $paymentEntity = $transformer->toEntity($paymentModel);


        $this->assertInstanceOf(PaymentEntity::class, $paymentEntity);
        $this->assertEquals($id, $paymentEntity->getId());
        $this->assertEquals('txn123', $paymentEntity->getTransactionId());
        $this->assertEquals(100, $paymentEntity->getAmount());
        $this->assertEquals('USD', $paymentEntity->getCurrency());
        $this->assertEquals(new \DateTimeImmutable('2023-01-01'), $paymentEntity->getCreatedDate());
    }
}
