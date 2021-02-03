<?php

namespace App\Listeners;

use App\Events\ModelDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DeleteModelSearchText
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
     * @param  ModelDeleted  $event
     * @return void
     */
    public function handle(ModelDeleted $event)
    {
        $model = $event->model;
        $model->searches()->delete();
    }
}
