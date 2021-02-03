<?php

namespace App\Payment\Click;

use Illuminate\Support\Facades\Log;

class Application
{
    const ACTION_PREPARE = 0;
    const ACTION_COMPLETE = 1;

    public $config;
    public $request;

    /**
     * Application constructor.
     * @param array $config configuration array with <em>merchant_id</em>, <em>login</em>, <em>keyFile</em> keys.
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->request = request();
    }

    /**
     * Authorizes session and handles requests.
     */
    public function run()
    {
        // check request from click
        $this->checkRequest();

        // authorize
        $this->authorize();

        // handle request
        switch ($this->request->action) {
            case static::ACTION_PREPARE:
                $this->prepare();
                break;
            case static::ACTION_COMPLETE:
                $this->complete();
                break;
            default:
                throw new ClickException(ClickException::ERROR_ACTION_NOT_FOUND, __('Action not found'));
                break;
        }
    }

    private function prepare()
    {
        // get or create payment
        $payment = $this->getPayment();

        $response = [
            'merchant_trans_id' => $this->request->merchant_trans_id,
            'merchant_prepare_id' => $payment->id,
        ];

        $this->send($response);
    }

    private function complete()
    {
        // get the payment
        $payment = $this->getPayment($this->request->merchant_prepare_id);

        $response = [
            'merchant_trans_id' => $this->request->merchant_trans_id,
            'merchant_confirm_id' => $payment->id,
        ];

        if ($this->request->error == 0) {
            // customer paid - set transaction confirmed, order paid
            $payment->setPaid();
            $order = $this->getOrder();
            $order->setPaid();
        } elseif ($this->request->error < 0) {
            // some error happened - cancel payment and throw exception
            Log::info(print_r($this->request->all(), 1));
            $payment->setCancelled();
            throw new ClickException(ClickException::ERROR_TRANSACTION_CANCELLED, 'Transaction cancelled');
        }

        $this->send($response);
    }

    private function send($response)
    {
        $response = array_merge([
            'click_trans_id' => $this->request->click_trans_id,
            'error' => 0,
            'error_note' => __('Success'),
        ], $response);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    private function checkRequest()
    {
        // check request params
        if(
            ($this->request->missing(['click_trans_id', 'service_id', 'merchant_trans_id', 'amount', 'action', 'error', 'error_note', 'sign_time', 'sign_string', 'click_paydoc_id'])) ||
            ($this->request->action == 1 && $this->request->input('merchant_prepare_id', '') == '')
        ) {
            throw new ClickException(ClickException::ERROR_IN_REQUEST_FROM_CLICK, __( 'Error in request from click' ));
        }

        // get order or throw order not found exception
        $order = $this->getOrder();

        // check amount
        if ($order->getTotal() != $this->request->amount) {
            throw new ClickException(ClickException::ERROR_INCORRECT_AMOUNT, __('Incorrect amount'));
        }

        // check if already paid
        if ($order->isPaid()) {
            throw new ClickException(ClickException::ERROR_ALREADY_PAID, __('Already paid'));
        }

        // check if cancelled before
        if ($order->isCancelled()) {
            throw new ClickException(ClickException::ERROR_TRANSACTION_CANCELLED, __('Transaction cancelled'));
        }
    }

    private function authorize()
    {
        if ( $this->getSignString() !== $this->request->sign_string ) {
            throw new ClickException(ClickException::ERROR_SIGN_CHECK_FAILED, __('Sign check error'));
        }
    }

    private function getOrder()
    {
        $order = ($this->config['model'])::find($this->request->merchant_trans_id);
        if (!$order) {
            throw new ClickException(ClickException::ERROR_ORDER_DOES_NOT_EXIST, __('Order does not exist'));
        }
        return $order;
    }

    private function getPayment($merchantPrepareID = null)
    {
        if ($merchantPrepareID) {
            // get payment by id
            $payment = Payment::find($merchantPrepareID);
            if (!$payment) {
                throw new ClickException(ClickException::ERROR_TRANSACTION_DOES_NOT_EXIST, 'Transaction not found');
            }

            // check if payment is already paid
            if ($payment->isPaid()) {
                throw new ClickException(ClickException::ERROR_ALREADY_PAID, __('Already paid'));
            }

            // check if payment is cancelled before
            if ($payment->isCancelled()) {
                throw new ClickException(ClickException::ERROR_TRANSACTION_CANCELLED, __('Transaction cancelled'));
            }

        } else {
            // get payment by merchant_trans_id or create new
            $order = $this->getOrder();
            $payment = Payment::where(['merchant_trans_id' => $this->request->merchant_trans_id, 'status' => PaymentStatus::WAITING])->firstOrCreate([
                'merchant_trans_id' => $this->request->merchant_trans_id,
                'status' => PaymentStatus::WAITING,
                'amount' => $order->getTotal(),
                'currency' => 'UZS',
            ]);
        }
        return $payment;
    }

    private function getSignString()
    {
        $request = $this->request;
        return md5(
            $request->click_trans_id .
            $request->service_id .
            $this->config['secret_key'] .
            $request->merchant_trans_id .
            ($request->action == static::ACTION_COMPLETE ? $request->merchant_prepare_id : '') .
            $request->amount .
            $request->action .
            $request->sign_time);
    }
}
