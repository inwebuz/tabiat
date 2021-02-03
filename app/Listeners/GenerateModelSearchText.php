<?php

namespace App\Listeners;

use App\Events\ModelSaved;
use App\Search;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GenerateModelSearchText
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
     * @param  ModelSaved  $event
     * @return void
     */
    public function handle(ModelSaved $event)
    {
        $model = $event->model;
        $model->searches()->delete();

        if (isset($model->status) && $model->status != 1) {
            return;
        }

        $searchBody = '';
        if (!empty($model->name)) {
            $searchBody .= $model->name . PHP_EOL;
        }
        if (!empty($model->description)) {
            $searchBody .= $model->description . PHP_EOL;
        }
        if (!empty($model->body)) {
            $searchBody .= strip_tags($model->body) . PHP_EOL;
        }
        if (!empty($model->first_name)) {
            $searchBody .= $model->first_name . PHP_EOL;
        }
        if (!empty($model->last_name)) {
            $searchBody .= $model->last_name . PHP_EOL;
        }
        if (!empty($model->middle_name)) {
            $searchBody .= $model->middle_name . PHP_EOL;
        }

        $currentLocale = app()->getLocale();

        foreach (config('laravellocalization.supportedLocales') as $key => $value) {
            if ($key != $currentLocale) {
                $model = $model->translate($key);
                if ($model->name) {
                    $searchBody .= $model->name . PHP_EOL;
                }
                if ($model->description) {
                    $searchBody .= $model->description . PHP_EOL;
                }
                if ($model->body) {
                    $searchBody .= strip_tags($model->body) . PHP_EOL;
                }
                if ($model->first_name) {
                    $searchBody .= $model->first_name . PHP_EOL;
                }
                if ($model->last_name) {
                    $searchBody .= $model->last_name . PHP_EOL;
                }
                if ($model->middle_name) {
                    $searchBody .= $model->middle_name . PHP_EOL;
                }
            }
        }

        $search = new Search();
        $search->body = $searchBody;

        $model->getModel()->searches()->save($search);
    }
}
