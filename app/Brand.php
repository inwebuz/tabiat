<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Traits\Resizable;
use TCG\Voyager\Traits\Translatable;

class Brand extends Model
{
    use Resizable;
    use Translatable;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public static $imgSizes = [
        'medium' => [202, 100],
    ];

    protected $translatable = ['name', 'description', 'body', 'seo_title', 'meta_description', 'meta_keywords'];

    protected $guarded = [];

    /**
     * Get the products.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * scope active
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Get main image
     */
    public function getImgAttribute()
    {
        return $this->image ? Voyager::image($this->image) : asset('images/brand/no-image.jpg');
    }

    /**
     * Get medium image
     */
    public function getMediumImgAttribute()
    {
        return $this->image ? Voyager::image($this->getThumbnail($this->image, 'medium')) : asset('images/brand/no-image.jpg');
    }

    /**
     * Get url
     */
    public function getURLAttribute()
    {
        return LaravelLocalization::localizeURL('brand/' . $this->id . '-' . $this->slug);
    }
}
