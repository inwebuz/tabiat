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

class Publication extends Model
{
    use Translatable;
    use Resizable;

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
     * Types.
     */
    const TYPE_PUBLICATION = 1;
    const TYPE_NEWS = 2;
    const TYPE_AD = 3;
    const TYPE_USEFUL_LINK = 4;
    const TYPE_DOCUMENT = 5;
    const TYPE_PROJECT = 6;
    const TYPE_FAQ = 7;
    const TYPE_EVENT = 8;
    const TYPE_ANNOUNCEMENT = 9;
    const TYPE_MASS_MEDIA = 10;
    const TYPE_COMPETITION = 11;
    const TYPE_ARTICLE = 12;
    const TYPE_TENDER = 13;

    public static $statuses = [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_PENDING];

    public static $imgSizes = [
        //'micro' => [120, 76],
        //'small' => [160, 102],
        'medium' => [328, 230],
        //'large' => [685, 480],
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
            $this->user_id = auth()->user()->id;
        }
        parent::save();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
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

    public function scopeNews($query)
    {
        return $query->where('type', static::TYPE_NEWS);
    }

    public function scopeEvents($query)
    {
        return $query->where('type', static::TYPE_EVENT);
    }

    public function scopeFaq($query)
    {
        return $query->where('type', static::TYPE_FAQ);
    }

    public function scopeUsefulLinks($query)
    {
        return $query->where('type', static::TYPE_USEFUL_LINK);
    }

    public function scopeAnnouncements($query)
    {
        return $query->where('type', static::TYPE_ANNOUNCEMENT);
    }

    public function scopeAds($query)
    {
        return $query->where('type', static::TYPE_AD);
    }

    public function scopePublications($query)
    {
        return $query->where('type', static::TYPE_PUBLICATION);
    }

    public function scopeDocuments($query)
    {
        return $query->where('type', static::TYPE_DOCUMENT);
    }

    public function scopeProjects($query)
    {
        return $query->where('type', static::TYPE_PROJECT);
    }

    public function scopeMassMedia($query)
    {
        return $query->where('type', static::TYPE_MASS_MEDIA);
    }

    public function scopeCompetitions($query)
    {
        return $query->where('type', static::TYPE_COMPETITION);
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
        $url = 'publications/' . $this->id . '-' . $slug;
        return LaravelLocalization::localizeURL($url, $lang);
    }

    /**
     * Get print url
     */
    public function getPrintURLAttribute()
    {
        return route('publications.print', ['publication' => $this->id, 'slug' => $this->slug]);
    }

    /**
     * Get main image
     */
    public function getImgAttribute()
    {
        return $this->image ? Voyager::image($this->image) : asset('img/publications/no-image.jpg');
    }

    /**
     * Get micro image
     */
    public function getMicroImgAttribute()
    {
        return $this->image ? Voyager::image($this->getThumbnail($this->image, 'micro')) : asset('img/publications/no-image-micro.jpg');
    }

    /**
     * Get small image
     */
    public function getSmallImgAttribute()
    {
        return $this->image ? Voyager::image($this->getThumbnail($this->image, 'small')) : asset('img/publications/no-image-small.jpg');
    }

    /**
     * Get medium image
     */
    public function getMediumImgAttribute()
    {
        return $this->image ? Voyager::image($this->getThumbnail($this->image, 'medium')) : asset('img/publications/no-image-medium.jpg');
    }

    /**
     * Get large image
     */
    public function getLargeImgAttribute()
    {
        return $this->image ? Voyager::image($this->getThumbnail($this->image, 'large')) : asset('img/publications/no-image-large.jpg');
    }

    /**
     * Get background image
     */
    public function getBgAttribute()
    {
        return $this->background ? Voyager::image($this->background) : '';
    }

    public static function types()
    {
        return [
            self::TYPE_PUBLICATION => __('main.publication_type_publication'),
            self::TYPE_NEWS => __('main.publication_type_news'),
            self::TYPE_AD => __('main.publication_type_ad'),
            // self::TYPE_USEFUL_LINK => __('main.publication_type_useful_link'),
            // self::TYPE_DOCUMENT => __('main.publication_type_document'),
            // self::TYPE_PROJECT => __('main.publication_type_project'),
            // self::TYPE_FAQ => __('main.publication_type_faq'),
            // self::TYPE_EVENT => __('main.publication_type_event'),
            // self::TYPE_ANNOUNCEMENT => __('main.publication_type_announcement'),
            // self::TYPE_MASS_MEDIA => __('main.publication_type_mass_media'),
            // self::TYPE_COMPETITION => __('main.publication_type_competition'),
        ];
    }

    public function typePage()
    {
        $page = null;
        $pageSlug = '';
        switch ($this->type) {
            case self::TYPE_EVENT: $pageSlug = 'events'; break;
            case self::TYPE_FAQ: $pageSlug = 'faq'; break;
        }
        if ($pageSlug) {
            $page = Page::where('slug', $pageSlug)->active()->first();
        }
        return $page;
    }
}
