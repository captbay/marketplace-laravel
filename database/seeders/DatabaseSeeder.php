<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Fav_product;
use App\Models\Konsumen;
use App\Models\Pengusaha;
use App\Models\Penjualan_produk;
use App\Models\Produk;
use App\Models\Produk_image;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // create user role admin
        User::create([
            'password' => 'admin',
            'role' => 'admin',
            'email' => 'admin@gmail.com'
        ]);

        // Create pengusaha and konsumen base on user
        Toko::factory(10)->create(); // already create pengusaha and user
        Konsumen::factory(10)->create(); // already create user

        // produk
        Produk::factory(10)->create();
        // fav_product
        Fav_product::factory(10)->create();
        // Penjualan_produk
        Penjualan_produk::factory(10)->create();
        // produk_images
        Produk_image::factory(10)->create();
    }
}
