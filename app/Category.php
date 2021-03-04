<?php

namespace App;

use App\Events\ModelDeleted;
use App\Events\ModelSaved;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Models\Translation;
use TCG\Voyager\Traits\Resizable;
use TCG\Voyager\Traits\Translatable;

class Category extends Model
{
    use Resizable;
    use Translatable;

    const SHOW_IN_NONE = 0;
	const SHOW_IN_EVERYWHERE = 1;
	const SHOW_IN_MENU = 2;
	const SHOW_IN_HOME = 3;

	const STATUS_INACTIVE = 0;
	const STATUS_ACTIVE = 1;

    public $additional_attributes = ['full_name'];

    public static $imgSizes = [
        'micro' => [70, 70],
        'small' => [200, 200],
        'medium' => [400, 400],
    ];

    protected $translatable = ['name', 'slug', 'description', 'body', 'seo_title', 'meta_description', 'meta_keywords'];

    protected $guarded = [];

    protected $dispatchesEvents = [
        'saved' => ModelSaved::class,
        'deleted' => ModelDeleted::class,
    ];

    public function searches()
    {
        return $this->morphMany(Search::class, 'searchable');
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function products()
    {
        // return $this->hasMany(Product::class);
        return $this->belongsToMany(Product::class);
    }

    /**
     * Get all products.
     */
    public function allProducts()
    {
        $childrenIds = $this->childrenIds($this);
        return Product::whereIn('category_id', $childrenIds);
//        return Product::whereHas('categories', function ($query) use ($childrenIds) {
//            $query->whereIn('category_id', $childrenIds);
//        });
    }

    /**
     * Get all attribute values.
     */
    public function allBrands($query = false)
    {
        $locale = app()->getLocale();
        if (!$query) {
            $query = $this->products()->active();
        }
        $query->whereNotNull('products.brand_id');
        $brandIds = $query->get()->pluck('brand_id')->unique();
        if ($brandIds->isEmpty()) {
            return collect();
        }
        return Brand::active()->whereIn('id', $brandIds)->withTranslation($locale)->get();
    }

    /**
     * Get all attribute values.
     */
    public function allAttributeValueIds($query = false, $onlyUsedForFilter = true)
    {
        $locale = app()->getLocale();
        if (!$query) {
            $query = $this->products()->active();
        }
        $productIds = $query->pluck('products.id');
        $attributeValueIds = DB::table('attribute_value_product')->whereIn('product_id', $productIds)->pluck('attribute_value_id')->unique();
        $attributeValuesQuery = AttributeValue::whereIn('id', $attributeValueIds)->withTranslation($locale);
        if ($onlyUsedForFilter) {
            $attributeValuesQuery->where('used_for_filter', $onlyUsedForFilter);
        }
        return $attributeValuesQuery->pluck('id');
    }

    /**
     * Get all attribute values.
     */
    public function allAttributes($query = false, $onlyUsedForFilter = true)
    {
        $locale = app()->getLocale();
        $attributeValueIds = $this->allAttributeValueIds($query, $onlyUsedForFilter);
        $attributeIds = DB::table('attribute_values')->leftJoin('attributes', 'attribute_values.attribute_id', '=', 'attributes.id')->whereIn('attribute_values.id', $attributeValueIds->toArray())->pluck('attributes.id')->unique();
        $attributesQuery = Attribute::whereIn('attributes.id', $attributeIds->toArray())->withTranslation($locale)->with(['attributeValues' => function ($q1) use ($attributeValueIds) {
            $q1->whereIn('id', $attributeValueIds->toArray());
        }]);
        if ($onlyUsedForFilter) {
            $attributesQuery->where('used_for_filter', 1);
        }
        return $attributesQuery->get();
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
        $url = 'category/' . $this->id . '-' . $slug;
        return LaravelLocalization::localizeURL($url, $lang);
    }

    /**
     * Get main image
     */
    public function getImgAttribute()
    {
        return $this->image ? Voyager::image($this->image) : asset('images/category/no-image.jpg');
    }

    /**
     * Get micro image
     */
    public function getMicroImgAttribute()
    {
        return $this->image ? Voyager::image($this->getThumbnail($this->image, 'micro')) : asset('images/category/no-image-micro.jpg');
    }

    /**
     * Get small image
     */
    public function getSmallImgAttribute()
    {
        return $this->image ? Voyager::image($this->getThumbnail($this->image, 'small')) : asset('images/category/no-image-small.jpg');
    }

    /**
     * Get medium image
     */
    public function getMediumImgAttribute()
    {
        return $this->image ? Voyager::image($this->getThumbnail($this->image, 'medium')) : asset('images/category/no-image-medium.jpg');
    }

    /**
     * Get large image
     */
    public function getLargeImgAttribute()
    {
        return $this->image ? Voyager::image($this->getThumbnail($this->image, 'large')) : asset('images/category/no-image-large.jpg');
    }

    /**
     * Get full name (with parents' names)
     */
    public function getFullNameAttribute()
    {
        $names = array_reverse($this->fullName($this));
        $name = array_pop($names);
        if (count($names)) {
            $name .= ' (' . implode(' > ', $names) . ')';
        }
        return $name;
    }

    /**
     * Get full name (with parents' names)
     */
    public function getHierarchyNameAttribute()
    {
        $names = array_reverse($this->fullName($this));
        return implode(' > ', $names);
    }

    /**
     * Voyager name browse accessor
     */
    public function getNameBrowseAttribute()
    {
        return $this->full_name;
    }

    public function parentId()
    {
        return $this->belongsTo(self::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * scope active
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * scope active
     */
    public function scopeOnHomePage($query)
    {
        return $query->whereIn('show_in', [self::SHOW_IN_HOME, self::SHOW_IN_EVERYWHERE]);
    }

    /**
     * scope active
     */
    public function scopeParents($query)
    {
        return $query->where('parent_id', null);
    }

    public function childrenIds($category, $ids = [])
    {
        $ids[] = $category->id;
        if (!$category->children->isEmpty()) {
            foreach ($category->children as $child) {
                $ids = $this->childrenIds($child, $ids);
            }
        }
        return $ids;
    }

    private function fullName($category, $names = [])
    {
        $names[] = $category->name;
        if ($category->parent) {
            $names = $this->fullName($category->parent, $names);
        }
        return $names;
    }

    /**
     * Get entries filtered by translated value.
     *
     * @param string $field {required} the field your looking to find a value in.
     * @param string $operator {required} value you are looking for or a relation modifier such as LIKE, =, etc.
     * @param string $value {optional} value you are looking for. Only use if you supplied an operator.
     * @param string|array $locales {optional} locale(s) you are looking for the field.
     * @param bool $default {optional} if true checks for $value is in default database before checking translations.
     *
     * @return Builder
     * @example  Class::whereTranslation('title', '=', 'zuhause', ['de', 'iu'])
     * @example  $query->whereTranslation('title', '=', 'zuhause', ['de', 'iu'])
     *
     */
    public static function scopeOrWhereTranslation($query, $field, $operator, $value = null, $locales = null, $default = true)
    {
        if ($locales && !is_array($locales)) {
            $locales = [$locales];
        }
        if (!isset($value)) {
            $value = $operator;
            $operator = '=';
        }

        $self = new static();
        $table = $self->getTable();

        return $query->whereIn($self->getKeyName(), Translation::where('table_name', $table)
            ->where('column_name', $field)
            ->orWhere('value', $operator, $value)
            ->when(!is_null($locales), function ($query) use ($locales) {
                return $query->whereIn('locale', $locales);
            })
            ->pluck('foreign_key')
        )->when($default, function ($query) use ($field, $operator, $value) {
            return $query->orWhere($field, $operator, $value);
        });
    }

    public static function showInPlaces()
    {
        return [
            self::SHOW_IN_NONE => 'Не показывать',
            self::SHOW_IN_EVERYWHERE => 'Везде',
            self::SHOW_IN_MENU => 'В меню',
            self::SHOW_IN_HOME => 'На главной странице',
        ];
    }
}
