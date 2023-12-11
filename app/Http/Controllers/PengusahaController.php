<?php

namespace App\Http\Controllers;

use App\Models\Pengusaha;
use App\Http\Requests\StorePengusahaRequest;
use App\Http\Requests\UpdatePengusahaRequest;
use Illuminate\Http\Request;

class PengusahaController extends Controller
{

    // pengusaha
    public function pengusaha()
    {
        $data = Pengusaha::all();

        return response()->json([
            'success' => true,
            'message' => 'Success get Pengusaha',
            'data' => $data
        ], 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
