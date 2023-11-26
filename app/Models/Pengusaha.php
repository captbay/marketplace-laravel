<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengusaha extends Model
{
    use HasFactory;

    protected $table = 'pengusahas';

    protected $fillable = [
        'user_id',
        'name',
        'phone_number',
        'address',
        'gender',
        'profile_picture',
        'background_picture',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Has One Relationship (One to One) dari Pengusaha ke Toko
    public function toko()
    {
        return $this->hasOne(Toko::class, 'pengusaha_id');
    }
}
