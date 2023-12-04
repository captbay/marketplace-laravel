<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    use HasFactory;

    protected $table = 'tokos';
    protected $primaryKey = 'id';

    protected $fillable = [
        'pengusaha_id',
        'name',
        'phone_number',
        'address',
        'description',
        'toko_pp',
        'toko_bg',
    ];

    public function pengusaha()
    {
        return $this->belongsTo(Pengusaha::class, 'pengusaha_id');
    }

    // Has Many Relationship (One to Many) dari Toko ke Produk
    public function produk()
    {
        return $this->hasMany(Produk::class, 'toko_id');
    }
}
