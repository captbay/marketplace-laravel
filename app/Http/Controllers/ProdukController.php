<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Produk_image;
use App\Models\Toko;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // show the resource
        $produk = Produk::with('produk_image', 'toko');

        // if user is KONSUMEN
        if (Auth::user()->role == 'KONSUMEN') {
            //  with count fav_produk as is_is_favorites
            $produk = $produk->withCount(['fav_product as is_favorites' => function ($query) {
                $query->where('konsumen_id', Auth::user()->konsumen->id);
            }]);
        }

        // if have requeste category
        if ($request->category) {
            $produk->where('category', $request->category);
        }

        // if have requeste search
        if ($request->search) {
            $produk->where('name', 'like', '%' . $request->search . '%');
        }

        $produk = $produk->orderBy('created_at', 'desc')->get();


        return response()->json([
            'success' => true,
            'message' => 'Daftar data produk',
            'data' => $produk
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // if auth != pengusaha
            if (Auth::user()->role != 'PENGUSAHA') {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not allowed to access this resource',
                ], 403);
            }

            //  validate request
            $validatedData = Validator::make($request->all(), [
                'name' => 'required|string',
                'price' => 'required|integer',
                'category' => 'required|string',
                'description' => 'required|string',
                'stock' => 'required|integer',
                // multiple image
                'image' => 'required|array',
                'image.*' => 'image|mimes:png|max:2048',
            ]);

            // response error validation
            if ($validatedData->fails()) {
                return response()->json(['message' => $validatedData->errors()], 422);
            }

            // if auth != pengusaha toko_id != produk->toko_id
            if (Auth::user()->pengusaha->toko->id == null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Buatlah toko dulu',
                ], 403);
            }

            // find toko by id
            $toko = Toko::find(Auth::user()->pengusaha->toko->id);

            // if toko not exist
            if (!$toko) {
                return response()->json([
                    'success' => false,
                    'message' => 'Toko Not Found',
                ], 404);
            }

            // create produk
            $produk = Produk::create([
                'toko_id' => $toko->id,
                'name' => $request->name,
                'price' => $request->price,
                'category' => $request->category,
                'description' => $request->description,
                'stock' => $request->stock,
            ]);

            // if success create produk
            if ($produk) {
                foreach ($request->file('image') as $index => $image) {
                    // upload image to storage multiple times
                    $original_name = $image->getClientOriginalName();
                    $generated_name = $produk->name . '-' . $index . '-' . time() . '.' . $image->extension();

                    // menyimpan gambar
                    $image->storeAs('public/produk', $generated_name);

                    // create produk_image
                    Produk_image::create([
                        'produk_id' => $produk->id,
                        'original_name' => $original_name,
                        'generated_name' => 'produk/' . $generated_name,
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Produk Created',
                    'data'    => $produk
                ], 201);
            }

            // if failed create produk
            return response()->json([
                'success' => false,
                'message' => 'Produk Failed to Save',
            ], 409);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
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

        $data = Produk::with('produk_image', 'toko.pengusaha.user');

        // if user is KONSUMEN
        if (Auth::user()->role == 'KONSUMEN') {
            //  with count fav_produk as is_is_favorites
            $data = $data->withCount(['fav_product as is_favorites' => function ($query) {
                $query->where('konsumen_id', Auth::user()->konsumen->id);
            }]);
        }

        $data = $data->where('id', $id)->first();

        return response()->json([
            'success' => true,
            'message' => 'Detail Data Produk',
            'data' => $data
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // if auth != pengusaha
            if (Auth::user()->role != 'PENGUSAHA') {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not allowed to access this resource',
                ], 403);
            }
            // find produk by id
            $produk = Produk::find($id);

            // if produk not exist
            if (!$produk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk Not Found',
                ], 404);
            }

            // if auth != pengusaha toko_id != produk->toko_id
            if (Auth::user()->pengusaha->toko->id != $produk->toko_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bukan kamu yang punya toko',
                ], 403);
            }

            //  validate request
            $validatedData = Validator::make($request->all(), [
                'name' => 'required|string',
                'price' => 'required|integer',
                'category' => 'required|string',
                'description' => 'required|string',
                'stock' => 'required|integer',
                // multiple image
                'image' => 'array',
                'image.*' => 'image|mimes:png|max:2048',
            ]);

            // response error validation
            if ($validatedData->fails()) {
                return response()->json(['message' => $validatedData->errors()], 422);
            }

            // update produk
            $produk->update([
                'name' => $request->name,
                'price' => $request->price,
                'category' => $request->category,
                'description' => $request->description,
                'stock' => $request->stock,
            ]);

            // if success update produk
            if ($produk) {
                // if have requeste image
                if ($request->image) {
                    // delete all produk_image by produk_id
                    $produk_image = Produk_image::where('produk_id', $produk->id)->get();
                    foreach ($produk_image as $image) {
                        // delete image from storage
                        unlink(public_path('storage/public/' . $image->generated_name));

                        // delete produk_image
                        $image->delete();
                    }

                    foreach ($request->file('image') as $index => $image) {
                        // upload image to storage multiple times
                        $original_name = $image->getClientOriginalName();
                        $generated_name = $produk->name . '-' . $index . '-' . time() . '.' . $image->extension();

                        // menyimpan gambar
                        $image->storeAs('public/produk', $generated_name);

                        // create produk_image
                        Produk_image::create([
                            'produk_id' => $produk->id,
                            'original_name' => $original_name,
                            'generated_name' => 'produk/' . $generated_name,
                        ]);
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Produk Updated',
                    'data'    => $produk
                ], 200);
            }

            // if failed update produk
            return response()->json([
                'success' => false,
                'message' => 'Produk Failed to Update',
            ], 409);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
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

        // if auth != pengusaha
        if (Auth::user()->role != 'PENGUSAHA') {
            return response()->json([
                'success' => false,
                'message' => 'You are not allowed to access this resource',
            ], 403);
        }

        // if auth != pengusaha toko_id != produk->toko_id
        if (Auth::user()->pengusaha->toko->id != $produk->toko_id) {
            return response()->json([
                'success' => false,
                'message' => 'Bukan kamu yang punya toko',
            ], 403);
        }

        try {
            // delete all produk_image by produk_id
            $produk_image = Produk_image::where('produk_id', $produk->id)->get();
            foreach ($produk_image as $image) {
                // delete image from storage
                unlink(public_path('storage/public/' . $image->generated_name));

                // delete produk_image
                $image->delete();
            }

            // delete produk
            $produk->delete();

            // return response success
            return response()->json([
                'success' => true,
                'message' => 'Produk Deleted',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $th->getMessage(),
            ], 500);
        }
    }
}
