<?php

namespace App\Domain\Payment;

class PaymentFailException extends \RuntimeException
{
    protected $message = 'Payment failed';
    protected  $code = 500;

}