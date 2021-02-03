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

class Partner extends Model
{
    use Translatable;
    use Resizable;

    public static $imgSizes = [
        'medium' => [250, 100],
    ];

    protected $translatable = ['name'];

    protected $guarded = [];

    public function getURLAttribute()
    {
        return LaravelLocalization::localizeURL('page/' . $this->id);
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
}
