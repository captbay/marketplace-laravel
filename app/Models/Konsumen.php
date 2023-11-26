<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsumen extends Model
{
    use HasFactory;

    protected $table = 'konsumens';

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

    // Has Many Relationship (One to Many) dari Konsumen ke Penjualan_produk
    public function penjualan_produk()
    {
        return $this->hasMany(Penjualan_produk::class, 'konsumen_id');
    }

    // Has Many Relationship (One to Many) dari Konsumen ke Fav_product
    public function fav_product()
    {
        return $this->hasMany(Fav_product::class, 'konsumen_id');
    }
}
