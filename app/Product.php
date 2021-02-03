<?php

namespace App;

use App\Events\ModelDeleted;
use App\Events\ModelSaved;
use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Models\Translation;
use TCG\Voyager\Traits\Resizable;
use TCG\Voyager\Traits\Translatable;

class Product extends Model
{
    use Resizable;
    use Translatable;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_PENDING = 2;

    const FEATURED_INACTIVE = 0;
    const FEATURED_ACTIVE = 1;

    const NEW_INACTIVE = 0;
    const NEW_ACTIVE = 1;

    /**
     * List of statuses.
     *
     * @var array
     */
    public static $statuses = [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_PENDING];

    public static $imgSizes = [
        //'micro' => [100, 125],
        'small' => [328, 360],
        //'medium' => [447, 560],
        'large' => [685, 751],
    ];

    protected $translatable = ['name', 'description', 'body', 'specifications', 'seo_title', 'meta_description', 'meta_keywords'];

    protected $guarded = [];

    protected $dispatchesEvents = [
        'saved' => ModelSaved::class,
        'deleted' => ModelDeleted::class,
    ];

    protected static function boot()
    {
        parent::boot();
        self::saving(function ($model) {
            if (!$model->user_id && auth()->user()) {
                $model->user_id = auth()->user()->getKey();
            }
        });
        self::created(function ($model) {
            // Helper::addInitialReview($model);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function searches()
    {
        return $this->morphMany(Search::class, 'searchable');
    }

    public function activeReviews()
    {
        return $this->reviews()->active();
    }

    /**
     * Get the attributes.
     */
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class)->withPivot('used_for_variations', 'order');
    }

    /**
     * Get the attributes used for variations.
     */
    public function variantAttributes()
    {
        return $this->attributes()->wherePivot('used_for_variations', 1);
    }

    /**
     * Get the attributes used for variations with values.
     */
    public function variantAttributesWithValues()
    {
        return $this->variantAttributes()->with([
            'attributeValues' => function ($query) {
                $query->whereIn('id', $this->attributeValuesIds());
            }
        ]);
    }

    /**
     * Get the attribute values.
     */
    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class);
    }

    /**
     * Get the attribute values ids.
     */
    public function attributeValuesIds()
    {
        return $this->attributeValues()->pluck('attribute_value_id');
    }

    /**
     * Get the attributes ordered.
     */
    public function attributesOrdered()
    {
        return $this->attributes()->orderBy('pivot_order');
    }

    /**
     * Get the product variants.
     */
    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Get url
     */
    public function getURLAttribute()
    {
        return LaravelLocalization::localizeURL('product/' . $this->id . '-' . $this->slug);
    }

    /**
     * Get background image
     */
    public function getBgAttribute()
    {
        return Voyager::image($this->background);
    }

    /**
     * Get main image
     */
    public function getImgAttribute()
    {
        return $this->image ? Voyager::image($this->image) : asset('images/product/no-image.jpg');
    }

    /**
     * Get micro image
     */
    public function getMicroImgAttribute()
    {
        return $this->image ? Voyager::image($this->getThumbnail($this->image, 'micro')) : asset('images/product/no-image-micro.jpg');
    }

    /**
     * Get small image
     */
    public function getSmallImgAttribute()
    {
        return $this->image ? Voyager::image($this->getThumbnail($this->image, 'small')) : asset('images/product/no-image-small.jpg');
    }

    /**
     * Get medium image
     */
    public function getMediumImgAttribute()
    {
        return $this->image ? Voyager::image($this->getThumbnail($this->image, 'medium')) : asset('images/product/no-image-medium.jpg');
    }

    /**
     * Get large image
     */
    public function getLargeImgAttribute()
    {
        return $this->image ? Voyager::image($this->getThumbnail($this->image, 'large')) : asset('images/product/no-image-large.jpg');
    }

    /**
     * Get second small image
     */
    public function getSecondSmallImgAttribute()
    {
        return $this->gallery_small_img ? $this->gallery_small_img : $this->small_img;
    }

    /**
     * Get second medium image
     */
    public function getSecondMediumImgAttribute()
    {
        return $this->gallery_medium_img ? $this->gallery_medium_img : $this->medium_img;
    }

    /**
     * Get gallery micro images
     */
    public function getMicroImgsAttribute()
    {
        return $this->getImgsGroup($this->images, 'micro');
    }

    /**
     * Get gallery small images
     */
    public function getSmallImgsAttribute()
    {
        return $this->getImgsGroup($this->images, 'small');
    }

    /**
     * Get gallery medium images
     */
    public function getMediumImgsAttribute()
    {
        return $this->getImgsGroup($this->images, 'medium');
    }

    /**
     * Get gallery large images
     */
    public function getLargeImgsAttribute()
    {
        return $this->getImgsGroup($this->images, 'large');
    }

    /**
     * Get gallery original images
     */
    public function getImgsAttribute()
    {
        return $this->getImgsGroup($this->images);
    }

    /**
     * Get gallery small first image
     */
    public function getGallerySmallImgAttribute()
    {
        $images = $this->getImgsGroup($this->images, 'small');
        return $images[0] ?? '';
    }

    /**
     * Get gallery medium first image
     */
    public function getGalleryMediumImgAttribute()
    {
        $images = $this->getImgsGroup($this->images, 'medium');
        return $images[0] ?? '';
    }

    /**
     * get raw images
     */
    private function getImgsGroup($images, $type = '')
    {
        $group = [];
        $getImages = json_decode($images);
        if (is_array($getImages)) {
            foreach ($getImages as $value) {
                $group[] = ($type == '') ? Voyager::image($value) : Voyager::image($this->getThumbnail($value, $type));
            }
        }
        return $group;
    }

    /**
     * Get active reviews average rating
     */
    public function getRatingAvgAttribute()
    {
        return $this->activeReviews->avg('rating');
    }

    /**
     * Get active reviews count
     */
    public function getRatingCountAttribute()
    {
        return $this->activeReviews->count();
    }

    /**
     * Get current price
     */
    public function getCurrentPriceAttribute()
    {
        return $this->isDiscounted() ? $this->sale_price : $this->price;
        //return $this->isDiscounted() ? ($this->price * (1 - $this->discount / 100)) : $this->price;
    }

    public function getShortNameAttribute()
    {
        return Str::limit($this->name, 40, ' ...');
    }

    public function getShortDescriptionAttribute()
    {
        return Str::words($this->description, 10, ' ...');
    }

    /**
     * Get status title
     */
    public function getStatusTitleAttribute()
    {
        return static::statuses()[$this->status];
    }

    public static function statuses() {
        return [
            static::STATUS_INACTIVE => __('main.status_inactive'),
            static::STATUS_PENDING => __('main.status_pending'),
            static::STATUS_ACTIVE => __('main.status_active'),
        ];
    }

    /**
     * check if product is simple
     */
    public function isSimple()
    {
        return $this->type == static::TYPE_SIMPLE;
    }

    /**
     * check if product is composite
     */
    public function isComposite()
    {
        return $this->type == static::TYPE_COMPOSITE;
    }

    /**
     * check if product has valid discount
     */
    public function isDiscounted()
    {
        return ($this->sale_price > 0 && $this->sale_price < $this->price);
        //return ($this->is_special && $this->discount > 0 && $this->discount <= 100);
    }

    /**
     * check if product is in stock
     */
    public function isAvailable()
    {
        return ($this->in_stock > 0);
    }

    /**
     * check if product is not in stock
     */
    public function isNotAvailable()
    {
        return ($this->in_stock == 0);
    }

    /**
     * check if product is under order
     */
    public function isUnderOrder()
    {
        return ($this->in_stock == static::STOCK_UNDER_ORDER);
    }

    /**
     * scope active
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * scope similar
     */
    public function scopeSimilar($query, Product $product)
    {
        return $query->where([['category_id', $product->category->id], ['id', '!=', $product->id]]);
    }

    /**
     * scope popular
     */
    public function scopePopular($query, Category $category)
    {
        return $query->where([['category_id', $category->id], ['is_popular', Product::POPULAR_ACTIVE]]);
    }

    /**
     * scope featured
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', self::FEATURED_ACTIVE);
    }

    /**
     * scope featured
     */
    public function scopeNew($query)
    {
        return $query->where('is_new', self::NEW_ACTIVE);
    }
}
