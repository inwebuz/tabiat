<?php

namespace App\Payment\Click;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'click_payments';

    protected $guarded = [];

    public function isPaid()
    {
        return $this->status == PaymentStatus::CONFIRMED;
    }

    public function setPaid()
    {
        $this->status = PaymentStatus::CONFIRMED;
        $this->save();
    }

    public function isCancelled()
    {
        return $this->status == PaymentStatus::REJECTED;
    }

    public function setCancelled()
    {
        $this->status = PaymentStatus::REJECTED;
        $this->save();
    }
}
