<?php

namespace App\Service;

use App\Domain\Card\Card;
use App\Entity;
use App\Repository\CardRepository;

readonly class CardTransformer
{
    public function __construct(private CardRepository $cardRepository){}
    public function toEntity(Card $model): Entity\Card
    {
        $entity = $this->cardRepository->findOneBy(['id' => $model->getId()]);
        if ($entity === null) {
            $entity = new Entity\Card();
        }
        $entity
            ->setId($model->getId())
            ->setCvv($model->getCvv())
            ->setCardNumber($model->getCardNumber())
            ->setExpiryMonth($model->getExpiryMonth())
            ->setExpiryYear($model->getExpiryYear());
        return $entity;
    }
}