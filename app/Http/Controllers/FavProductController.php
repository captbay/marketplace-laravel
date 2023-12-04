<?php

namespace App\Http\Controllers;

use App\Models\Fav_product;
use App\Http\Requests\StoreFav_productRequest;
use App\Http\Requests\UpdateFav_productRequest;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // get all data fav_product
        $fav_product = Fav_product::with(['produk' => function ($q) {
            $q->with('produk_image', 'toko');
        }])
            ->where('konsumen_id', Auth::user()->id)
            ->get();

        // return response
        return response()->json([
            'success' => true,
            'message' => 'List Data Fav_product',
            'data'    => $fav_product
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($id)
    {
        // find produk by id
        $produk = Produk::find($id);

        // if produk not exist
        if (!$produk) {
            return response()->json([
                'success' => false,
                'message' => 'Produk Not Found',
            ], 404);
        }

        // find fav_product by id
        $fav_product = Fav_product::where('produk_id', $id)->where('konsumen_id', Auth::user()->id)->first();

        // if fav_product exist delete
        if ($fav_product) {
            $fav_product->delete();

            // return response success
            return response()->json([
                'success' => true,
                'message' => 'Fav_product Deleted',
            ], 200);
        }

        // if fav_product not exist create new fav_product
        $data = Fav_product::create([
            'konsumen_id' => Auth::user()->id,
            'produk_id'   => $id
        ]);

        // return response success
        return response()->json([
            'success' => true,
            'message' => 'Fav_product Created',
            'data'    => $data
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
