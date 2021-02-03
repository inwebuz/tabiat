<?php

namespace App\Providers;

use App\Events\ModelDeleted;
use App\Events\ModelSaved;
use App\Listeners\DeleteModelSearchText;
use App\Listeners\GenerateModelSearchText;
use App\Listeners\VoyagerBreadDataSave;
use App\Shop;
use App\User;
use App\UserApplication;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Models\Role;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // Voyager Admin Custom Listeners
        BreadDataAdded::class => [
            VoyagerBreadDataSave::class,
        ],
        BreadDataUpdated::class => [
            VoyagerBreadDataSave::class,
        ],
        ModelSaved::class => [
            GenerateModelSearchText::class,
        ],
        ModelDeleted::class => [
            DeleteModelSearchText::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        UserApplication::saved(function($model){
            if ($model->status == UserApplication::STATUS_APPROVED) {
                $user = User::findOrFail($model->user_id);
                $user->role_id = Role::where('name', 'seller')->firstOrFail()->id;
                $user->save();
                Shop::firstOrCreate(['user_id' => $user->id], [
                    'name' => 'Shop',
                    'slug' => Str::slug('Shop'),
                    'status' => Shop::STATUS_PENDING,
                ]);
            }
        });
    }
}
