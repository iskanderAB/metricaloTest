<?php

namespace App\Controller\Checkout\Event;

use App\Http\Response\AbstractResponseEvent;
use phpDocumentor\Reflection\Types\Context;

readonly class CheckoutSuccess extends AbstractResponseEvent
{
    public const MESSAGE = 'checkout success';
    public const CODE = 200;
}
