<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Traits\Resizable;
use TCG\Voyager\Traits\Translatable;

class Shop extends Model
{
    use Resizable;
    use Translatable;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_PENDING = 2;

    public static $imgSizes = [
        'small' => [100, 100],
        'medium' => [200, 200],
    ];

    protected $translatable = ['name', 'description', 'body', 'seo_title', 'meta_description', 'meta_keywords'];

    public $additional_attributes = ['full_name'];

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getFullNameAttribute()
    {
        return '#' . $this->id . ' ' . $this->name;
    }

    /**
     * Get url
     */
    public function getURLAttribute()
    {
        return LaravelLocalization::localizeURL('shop/' . $this->id);
    }

    /**
     * Get main image
     */
    public function getImgAttribute()
    {
        return $this->image ? Voyager::image($this->image) : asset('images/shop/no-image.jpg');
    }

    /**
     * Get small image
     */
    public function getSmallImgAttribute()
    {
        return $this->image ? Voyager::image($this->getThumbnail($this->image, 'small')) : asset('images/shop/no-image-small.jpg');
    }

    /**
     * Get medium image
     */
    public function getMediumImgAttribute()
    {
        return $this->image ? Voyager::image($this->getThumbnail($this->image, 'medium')) : asset('images/shop/no-image-medium.jpg');
    }

    /**
     * scope active
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }
}
