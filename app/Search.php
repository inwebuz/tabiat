<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    public function searchable()
    {
        return $this->morphTo();
    }
}
