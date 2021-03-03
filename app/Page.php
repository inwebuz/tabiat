<?php

namespace App;

use App\Events\ModelDeleted;
use App\Events\ModelSaved;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Traits\Resizable;
use TCG\Voyager\Traits\Translatable;

class Page extends Model
{
    use Translatable;
    use Resizable;

    /**
     * Statuses.
     */
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * Show control.
     */
    const SHOW_IN_NONE = 0;
    const SHOW_IN_HEADER = 1;
    const SHOW_IN_FOOTER = 2;
    const SHOW_IN_BOTH = 3;

    /**
     * List of statuses.
     *
     * @var array
     */
    public static $statuses = [self::STATUS_ACTIVE, self::STATUS_INACTIVE];

    public static $imgSizes = [
        'medium' => [380, 380],
    ];

    public static $galleryImgSizes = [
        'small' => [120, 90],
        'medium' => [400, 300],
    ];

    protected $translatable = ['name', 'slug', 'short_name', 'description', 'body', 'additional_info', 'seo_title', 'meta_description', 'meta_keywords'];

    protected $guarded = [];

    protected $dispatchesEvents = [
        'saved' => ModelSaved::class,
        'deleted' => ModelDeleted::class,
    ];

    public function save(array $options = [])
    {
        // If no author has been assigned, assign the current user's id as the author of the post
        if (!$this->user_id && auth()->user()) {
            $this->user_id = auth()->user()->getKey();
        }
        parent::save();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Page::class, 'parent_id', 'id');
    }

    public function pages()
    {
        return $this->hasMany(Page::class, 'parent_id', 'id');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function searches()
    {
        return $this->morphMany(Search::class, 'searchable');
    }

    public function scopeActive($query)
    {
        return $query->where('status', static::STATUS_ACTIVE);
    }

    public function scopeInHeaderMenu($query)
    {
        return $query->whereIn('show_in', [static::SHOW_IN_HEADER, static::SHOW_IN_BOTH]);
    }

    public function scopeInFooterMenu($query)
    {
        return $query->whereIn('show_in', [static::SHOW_IN_FOOTER, static::SHOW_IN_BOTH]);
    }

    /**
     * Get url
     */
    public function getURLAttribute()
    {
        return $this->getURL();
    }

    /**
     * Get url
     */
    public function getURL($lang = '')
    {
        if (!$lang) {
            $lang = app()->getLocale();
        }
        $slug = $this->getTranslatedAttribute('slug', $lang) ?: $this->slug;
        $url = Route::has($this->slug) ? route($this->slug) : 'page/' . $this->id . '-' . $slug;
        return LaravelLocalization::localizeURL($url, $lang);
    }

    /**
     * Get print url
     */
    public function getPrintURLAttribute()
    {
        return route('page.print', ['page' => $this->id, 'slug' => $this->slug]);
    }

    /**
     * Get main image
     */
    public function getImgAttribute()
    {
        return Voyager::image($this->image);
    }

    /**
     * Get medium image
     */
    public function getMediumImgAttribute()
    {
        return Voyager::image($this->getThumbnail($this->image, 'medium'));
    }

    /**
     * Get background image
     */
    public function getBgAttribute()
    {
        return $this->background ? Voyager::image($this->background) : '';
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

    public function getShortNameTextAttribute()
    {
        return $this->getTranslatedAttribute('short_name') ?: $this->getTranslatedAttribute('name');
    }

    public function getNameLimitedAttribute()
    {
        return Str::limit($this->getTranslatedAttribute('name'), 40, ' ...');
    }

    public function getDescriptionLimitedAttribute()
    {
        return Str::words($this->getTranslatedAttribute('description'), 10, ' ...');
    }

    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }

}
