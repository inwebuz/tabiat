<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class Order extends Model
{
    const STATUS_CANCELLED_AFTER_PAYMENT = -2;
    const STATUS_CANCELLED = -1;
    const STATUS_PENDING = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_PAID = 2;
    const STATUS_COMPLETED = 3;

    const COMMUNICATION_METHOD_PHONE = 0;
    const COMMUNICATION_METHOD_SMS = 1;
    const COMMUNICATION_METHOD_TELEGRAM = 2;

    const PAYMENT_METHOD_CASH = 1;
    const PAYMENT_METHOD_UZCARD = 2;
    const PAYMENT_METHOD_HUMO = 3;
    const PAYMENT_METHOD_PAYME = 4;
    const PAYMENT_METHOD_CLICK = 5;
    const PAYMENT_METHOD_APELSIN = 6;

    const TYPE_BUY_IMMEDIATELY = 0;
    const TYPE_VENDOO_INSTALLMENT = 1;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        self::updating(function ($model) {

        });
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function installmentOrder()
    {
        return $this->hasOne(InstallmentOrder::class);
    }

    /**
     * Get url
     */
    public function getURLAttribute()
    {
        return LaravelLocalization::localizeURL('order/' . $this->id . '-' . md5($this->created_at));
    }

    /**
     * Get status title
     */
    public function getStatusTitleAttribute()
    {
        return static::statuses()[$this->status];
    }

    /**
     * Get communication method title
     */
    public function getCommunicationMethodTitleAttribute()
    {
        return static::communicationMethods()[$this->communication_method];
    }

    /**
     * Get payment method title
     */
    public function getPaymentMethodTitleAttribute()
    {
        return static::paymentMethods()[$this->payment_method_id];
    }

    public static function statuses() {
        return [
            static::STATUS_CANCELLED_AFTER_PAYMENT => __('main.order_status_cancelled_after_payment'),
            static::STATUS_CANCELLED => __('main.order_status_cancelled'),
            static::STATUS_PENDING => __('main.order_status_pending'),
            static::STATUS_PROCESSING => __('main.order_status_processing'),
            static::STATUS_PAID => __('main.order_status_paid'),
            static::STATUS_COMPLETED => __('main.order_status_completed'),
        ];
    }

    public static function communicationMethods() {
        return [
            static::COMMUNICATION_METHOD_PHONE => __('main.communication_method_phone'),
            // static::COMMUNICATION_METHOD_SMS => __('main.communication_method_sms'),
            static::COMMUNICATION_METHOD_TELEGRAM => __('main.communication_method_telegram'),
        ];
    }

    public static function paymentMethods() {
        return [
            static::PAYMENT_METHOD_CASH => __('main.payment_method_cash'),
            static::PAYMENT_METHOD_UZCARD => __('main.payment_method_uzcard'),
            static::PAYMENT_METHOD_HUMO => __('main.payment_method_humo'),
            static::PAYMENT_METHOD_PAYME => __('main.payment_method_payme'),
            static::PAYMENT_METHOD_CLICK => __('main.payment_method_click'),
            // static::PAYMENT_METHOD_APELSIN => __('main.payment_method_apelsin'),
        ];
    }

    public static function types()
    {
        return [
            static::TYPE_BUY_IMMEDIATELY => __('main.buy_immediately'),
            // static::TYPE_VENDOO_INSTALLMENT => __('main.place_installment_order_through') . ' Vendoo',
        ];
    }

    public function isInstallmentOrder()
    {
        return $this->type == static::TYPE_VENDOO_INSTALLMENT;
    }

    public function isPending()
    {
        return $this->status == static::STATUS_PENDING;
    }

    public function isPaid()
    {
        return $this->status == static::STATUS_PAID;
    }

    public function getTotalReadAttribute()
    {
        return Helper::formatPrice($this->total);
    }

    public function getTotalTiyinAttribute()
    {
        return round($this->total * 100);
    }

    public function paycomTransactions()
    {
        return $this->hasMany(PaycomTransaction::class, 'order_id');
    }
}
