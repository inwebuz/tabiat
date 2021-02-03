<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use TCG\Voyager\Traits\Resizable;
use TCG\Voyager\Traits\Translatable;

class Attribute extends Model
{
    use Resizable;
    use Translatable;

    const TYPE_LIST = 0;
    const TYPE_SELECT = 1;
    const TYPE_RADIO_INPUTS = 2;
    const TYPE_BUTTONS = 3;
    const TYPE_COLORS = 4;

    protected $translatable = ['name'];

    protected $guarded = [];

    public function attributeValues()
    {
        return $this->hasMany(AttributeValue::class);
    }

    /**
     * Get products.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public static function types()
    {
        return [
            self::TYPE_LIST => __('main.attribute_type_list'),
            // self::TYPE_SELECT => __('main.attribute_type_select'),
            // self::TYPE_RADIO_INPUTS => __('main.attribute_type_radio_inputs'),
            self::TYPE_BUTTONS => __('main.attribute_type_buttons'),
            self::TYPE_COLORS => __('main.attribute_type_colors'),
        ];
    }
}
