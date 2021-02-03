<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'telegram-bot-nWq723bZP7x5cfF',
        'paycom-xBrGbjU2RyaNwBY',
        'click-SVNfd45qbr5dW9b/prepare',
        'click-SVNfd45qbr5dW9b/complete',
        'synchro/*',
    ];
}
