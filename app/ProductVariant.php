<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Resizable;
use TCG\Voyager\Traits\Translatable;

class ProductVariant extends Model
{
    use Resizable;
    use Translatable;

    /**
     * Statuses.
     */
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_PENDING = 2;

    /**
     * Featured.
     */
    const FEATURED_INACTIVE = 0;
    const FEATURED_ACTIVE = 1;

    /**
     * List of statuses.
     *
     * @var array
     */
    public static $statuses = [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_PENDING];

    protected $translatable = ['name'];

    protected $casts = [
        //'combination' => 'array',
    ];

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeValueIds()
    {
        return json_decode($this->combination, true);
    }

    public function getCurrentPriceAttribute()
    {
        return $this->isDiscounted() ? $this->sale_price : $this->price;
    }

    public function getCombinationNameAttribute()
    {
        return implode(',', $this->attributeValueIds());
    }

    public function getUrlAttribute()
    {
        return $this->product->url;
    }

    public function getSmallImgAttribute()
    {
        return $this->product->small_img;
    }

    public function getMicroImgAttribute()
    {
        return $this->product->micro_img;
    }

    /**
     * scope active
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * check if product has valid discount
     */
    public function isDiscounted()
    {
        return ($this->sale_price > 0 && $this->sale_price < $this->price);
    }
}
