<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk_image extends Model
{
    use HasFactory;

    protected $table = 'produk_images';

    protected $fillable = [
        'produk_id',
        'original_name',
        'generated_name',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}