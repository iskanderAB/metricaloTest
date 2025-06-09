<?php
declare(strict_types=1);

namespace App\Service;

use App\Domain\Payment\Payment;
use App\Entity;


readonly class PaymentTransformer
{
    public function toEntity(Payment $model): Entity\Payment
    {

        $entity = new Entity\Payment();

        $entity
            ->setId($model->getId())
            ->setTransactionId($model->getTransactionId())
            ->setAmount($model->getAmount())
            ->setCurrency($model->getCurrency())
            ->setCreatedDate($model->getCreatedDate());
        return $entity;
    }
}
