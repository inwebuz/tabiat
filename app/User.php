<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use TCG\Voyager\Facades\Voyager;

class User extends \TCG\Voyager\Models\User implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone_number', 'address', 'first_name', 'last_name',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_number_verified_at' => 'datetime',
    ];

    /**
     * Get url
     */
    public function getURLAttribute()
    {
        return LaravelLocalization::localizeURL('user/' . $this->id);
    }

    /**
     * Get main image
     */
    public function getAvatarImgAttribute()
    {
        return $this->avatar ? Voyager::image($this->avatar) : asset('temp/user/avatar.jpg');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function shops()
    {
        return $this->hasMany(Shop::class);
    }

    public function userApplications()
    {
        return $this->hasMany(UserApplication::class);
    }

    public function isPhoneVerified()
    {
        return $this->phone_number_verified_at;
    }

    public function isSeller()
    {
        return $this->role->name == 'seller';
    }
}
