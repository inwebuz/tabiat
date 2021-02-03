<?php

namespace App\Http\Controllers\Api;

use App\AttributeValue;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttributeValuesController extends Controller
{
    public function index()
    {
        return AttributeValue::all();
    }

    public function show(AttributeValue $attributeValue)
    {
        return $attributeValue;
    }
}
