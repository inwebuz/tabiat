<?php

namespace App\Http\Controllers\Api;

use App\Attribute;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttributesController extends Controller
{
    public function index()
    {
        return Attribute::all();
    }

    public function attributeValues(Attribute $attribute)
    {
        return $attribute->attributeValues;
    }

    public function show(Attribute $attribute)
    {
        return $attribute;
    }
}
