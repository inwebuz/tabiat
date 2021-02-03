<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use TCG\Voyager\Traits\Resizable;
use TCG\Voyager\Traits\Translatable;

class AttributeValue extends Model
{
    use Resizable;
    use Translatable;

    protected $translatable = ['name'];

    protected $guarded = [];

    /**
     * Get attribute.
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    /**
     * Get products.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
