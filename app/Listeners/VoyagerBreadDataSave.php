<?php

namespace App\Listeners;

use App\AttributeValue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;

class VoyagerBreadDataSave
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if($event->data) {

            // App\Attribute
            if($event->data instanceof \App\Attribute) {
                $attribute = $event->data;
                $attribute->id;

                $values = request()->input('attribute_values', []);

                // remove and update old values
                foreach($attribute->attributeValues as $attributeValue) {
                    if (!array_key_exists($attributeValue->id, $values)) {
                        $attributeValue->delete();
                    } else {
                        if (!empty($values[$attributeValue->id]['name'])) {
                            $attributeValue->update([
                                'name' => $values[$attributeValue->id]['name'],
                                'slug' => Str::slug($values[$attributeValue->id]['name']),
                            ]);
                            unset($values[$attributeValue->id]);
                        }
                    }
                }

                // add new values
                foreach($values as $value) {
                    if (!empty($value['name'])) {
                        AttributeValue::create([
                            'name' => $value['name'],
                            'slug' => Str::slug($value['name']),
                            'attribute_id' => $attribute->id
                        ]);
                    }
                }
            }
        }
    }
}
