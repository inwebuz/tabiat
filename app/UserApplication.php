<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserApplication extends Model
{
    /**
     * Statuses.
     */
    const STATUS_REJECTED = 0;
    const STATUS_APPROVED = 1;
    const STATUS_PENDING = 2;

    /**
     * Types.
     */
    const TYPE_BECOME_SELLER = 1;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get status title
     */
    public function getStatusTitleAttribute()
    {
        return static::statuses()[$this->status];
    }

    /**
     * Get type title
     */
    public function getTypeTitleAttribute()
    {
        return static::types()[$this->type];
    }

    public static function statuses() {
        return [
            static::STATUS_PENDING => __('main.status_pending'),
            static::STATUS_REJECTED => __('main.status_rejected'),
            static::STATUS_APPROVED => __('main.status_approved'),
        ];
    }

    public static function types() {
        return [
            static::TYPE_BECOME_SELLER => __('main.application_type_become_seller'),
        ];
    }

    /**
     * Get status read
     */
    public function getStatusReadAttribute()
    {
        return static::statuses()[$this->status];
    }

    /**
     * Get type read
     */
    public function getTypeReadAttribute()
    {
        return static::types()[$this->type];
    }

    public function getUserIdReadAttribute()
    {
        return $this->user->name . ' (' . $this->user->phone_number . ')';
    }

    public function getUserIdBrowseAttribute()
    {
        return $this->user->name . ' (' . $this->user->phone_number . ')';
    }
}
