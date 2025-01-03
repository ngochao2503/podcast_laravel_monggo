<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Eloquent\Model;

// use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Auth\User as Authenticatable;

use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Podcaster extends Authenticatable implements MustVerifyEmail

{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'google_id',
        'facebook_id',
        'email_verified_at'
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function podcasts()
    {
        return $this->hasMany(Podcast::class, 'podcaster_id');
    }

    public function followings()
    {
        return $this->hasMany(PodcasterFollower::class, 'podcaster_id');
    }

    public function followers()
    {
        return $this->hasMany(PodcasterFollower::class, 'follower_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'podcaster_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'podcaster_id');
    }
}
