<?php

namespace App\Payment\Click;

class ClickException extends \Exception
{
    const ERROR_TRANSACTION_CANCELLED       = -9;
    const ERROR_IN_REQUEST_FROM_CLICK       = -8;
    const ERROR_FAILED_TO_UPDATE_ORDER      = -7;
    const ERROR_TRANSACTION_DOES_NOT_EXIST  = -6;
    const ERROR_ORDER_DOES_NOT_EXIST        = -5;
    const ERROR_ALREADY_PAID                = -4;
    const ERROR_ACTION_NOT_FOUND            = -3;
    const ERROR_INCORRECT_AMOUNT            = -2;
    const ERROR_SIGN_CHECK_FAILED           = -1;

    public $code;
    public $message;

    public function __construct($code, $message)
    {
        parent::__construct();
        $this->code = $code;
        $this->message = $message;
    }

    public function send()
    {
        header('Content-Type: application/json; charset=UTF-8');

        // create response
        $response['error'] = $this->code;
        $response['error_note']  = $this->message;

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
