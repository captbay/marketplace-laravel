<?php

namespace App\Http\Controllers;

use App\Models\Fav_product;
use App\Http\Requests\StoreFav_productRequest;
use App\Http\Requests\UpdateFav_productRequest;

class FavProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFav_productRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Fav_product $fav_product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fav_product $fav_product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFav_productRequest $request, Fav_product $fav_product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fav_product $fav_product)
    {
        //
    }
}
