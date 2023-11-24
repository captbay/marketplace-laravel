<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produks';

    protected $fillable = [
        'toko_id',
        'name',
        'description',
        'price',
        'stock',
        'category',
    ];

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'toko_id');
    }

    // Has Many Relationship (One to Many) dari Produk ke Produk_image
    public function produk_image()
    {
        return $this->hasMany(Produk_image::class, 'produk_id');
    }

    // Has Many Relationship (One to Many) dari Produk ke Penjualan_produk
    public function penjualan_produk()
    {
        return $this->hasMany(Penjualan_produk::class, 'produk_id');
    }

    // Has Many Relationship (One to Many) dari Produk ke Fav_product
    public function fav_product()
    {
        return $this->hasMany(Fav_product::class, 'produk_id');
    }
}
