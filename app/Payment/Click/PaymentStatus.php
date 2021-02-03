<?php

namespace App\Payment\Click;

class PaymentStatus
{
    const INPUT = 'input';
    const WAITING = 'waiting';
    const PREAUTH = 'preauth';
    const CONFIRMED = 'confirmed';
    const REJECTED = 'rejected';
    const REFUNDED = 'refunded';
    const ERROR = 'error';
}
