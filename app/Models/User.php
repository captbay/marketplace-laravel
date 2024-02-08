<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Messages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'role',
        'fcm_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // hasOne relationship with Pengusaha
    public function pengusaha()
    {
        return $this->hasOne(Pengusaha::class, 'user_id');
    }

    // hasOne relationship with Konsumen
    public function konsumen()
    {
        return $this->hasOne(Konsumen::class, 'user_id');
    }

    public function messages(){
        return $this->hasMany(Messages::class, 'user_id');
    }

    public function sender(){
        return $this->hasOne(Conversations::class, 'sender_id');
    }

    public function receiver(){
        return $this->hasOne(Conversations::class, 'receiver_id');
    }

    public function routeNotificationForFcm()
    {
        // return "fQ90QIpqTk-ZVqxHo8Ojyi:APA91bFbcBQOPVPBRXUKy6hLlaPltf4c3E1u3x8CGSw_2492hq06-UA9JmH1-302MOHm--H4iUGnyGomzwWWdhYtLrxCJZrrd1grd2W9oh8-_tjEaazC36c1jjpzhd9j-HFiMWOnaUAw";
        return $this->fcm_token;
    }
}
