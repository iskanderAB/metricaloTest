<?php

namespace App\Domain\Card\Storage;

use App\Domain\Card\Card;
use App\Domain\Card\CardId;

interface CardRepositoryInterface
{
    public function insert(Card $card): CardId;
}