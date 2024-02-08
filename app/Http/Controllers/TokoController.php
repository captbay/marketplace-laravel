<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TokoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // get data toko
        $toko = Toko::with('produk.produk_image')->get();

        // return response
        return response()->json([
            'success' => true,
            'message' => 'List Data Toko',
            'data'    => $toko
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            //  validate data
            $validatedData = Validator::make($request->all(), [
                'name' => 'required|string|unique:tokos',
                'phone_number' => 'required|regex:/^(0)8[1-9][0-9]{6,10}$/',
                'address'    => 'required|string',
                'description'   => 'required|string',
                'toko_pp'      => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'toko_bg'      => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // response error validation
            if ($validatedData->fails()) {
                return response()->json(['message' => $validatedData->errors()], 422);
            }

            // check if auth user is pengusaha
            if (Auth::user()->pengusaha) {
                // upload new toko_pp
                $toko_pp = $request->file('toko_pp');
                $toko_pp_name = time() . '.' . $toko_pp->extension();
                $toko_pp->storeAs('public/toko_pp', $toko_pp_name);

                // upload new toko_bg
                $toko_bg = $request->file('toko_bg');
                $toko_bg_name = time() . '.' . $toko_bg->extension();
                $toko_bg->storeAs('public/toko_bg', $toko_bg_name);

                // create toko
                $toko = Toko::create([
                    'pengusaha_id' => Auth::user()->pengusaha->id,
                    'name' => $request->name,
                    'phone_number' => $request->phone_number,
                    'address'    => $request->address,
                    'description'   => $request->description,
                    'toko_pp'      => 'toko_pp/' . $toko_pp_name,
                    'toko_bg'      => 'toko_bg/' . $toko_bg_name,
                ]);

                // return response
                return response()->json([
                    'success' => true,
                    'message' => 'Toko Berhasil Dibuat',
                    'data'    => $toko
                ], 201);
            } else {
                // return response
                return response()->json([
                    'success' => false,
                    'message' => 'User Bukan Pengusaha',
                ], 401);
            }
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
        // get data toko by id
        $toko = Toko::with('produk.produk_image', 'pengusaha.user')->find($id);

        // check if data toko exists
        if (!$toko) {
            return response()->json([
                'success' => false,
                'message' => 'Data Toko Tidak Ditemukan',
            ], 404);
        }

        // return response
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Toko',
            'data'    => $toko
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // get data toko by id
            $toko = Toko::find($id);

            // check if data toko exists
            if (!$toko) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Toko Tidak Ditemukan',
                ], 404);
            }

            //  validate data
            $validatedData = Validator::make($request->all(), [
                'name' => 'required|string|unique:tokos,name,' . $id . ',id',
                'phone_number' => 'required|regex:/^(0)8[1-9][0-9]{6,10}$/',
                'address' => 'required|string',
                'description' => 'required|string',
                'toko_pp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'toko_bg' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // response error validation
            if ($validatedData->fails()) {
                return response()->json(['message' => $validatedData->errors()], 422);
            }

            // check if auth user is pengusaha
            if (Auth::user()->pengusaha) {
                // check if auth user is owner of the toko
                if (Auth::user()->pengusaha->id == $toko->pengusaha_id) {

                    // upload new toko_pp
                    if ($request->file('toko_pp')) {
                        // delete old toko_pp
                        if ($toko->toko_pp != null) {
                            $old_toko_pp = $toko->toko_pp;
                            $old_toko_pp_path = public_path('storage/public/' . $old_toko_pp);
                            unlink($old_toko_pp_path);
                        }

                        $toko_pp = $request->file('toko_pp');
                        $toko_pp_name = time() . '.' . $toko_pp->extension();
                        $toko_pp->storeAs('public/toko_pp', $toko_pp_name);
                        $toko_pp_path = 'toko_pp/' . $toko_pp_name;
                    } else {
                        $toko_pp_path = $toko->toko_pp;
                    }

                    // upload new toko_bg
                    if ($request->file('toko_bg')) {
                        // delete old toko_bg
                        if ($toko->toko_bg != null) {
                            $old_toko_bg = $toko->toko_bg;
                            $old_toko_bg_path = public_path('storage/public/' . $old_toko_bg);
                            unlink($old_toko_bg_path);
                        }

                        $toko_bg = $request->file('toko_bg');
                        $toko_bg_name = time() . '.' . $toko_bg->extension();
                        $toko_bg->storeAs('public/toko_bg', $toko_bg_name);
                        $toko_bg_path = 'toko_bg/' . $toko_bg_name;
                    } else {
                        $toko_bg_path = $toko->toko_bg;
                    }

                    // update toko
                    $toko->update([
                        'name' => $request->name,
                        'phone_number' => $request->phone_number,
                        'address'    => $request->address,
                        'description'   => $request->description,
                        'toko_pp'      => $toko_pp_path,
                        'toko_bg'      => $toko_bg_path,
                    ]);

                    // return response
                    return response()->json([
                        'success' => true,
                        'message' => 'Toko Berhasil Diupdate',
                        'data'    => $toko
                    ], 200);
                } else {
                    // return response
                    return response()->json([
                        'success' => false,
                        'message' => 'User Bukan Pemilik Toko',
                    ], 401);
                }
            } else {
                // return response
                return response()->json([
                    'success' => false,
                    'message' => 'User Bukan Pengusaha',
                ], 401);
            }
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
        try {
            //  get data toko by id
            $toko = Toko::find($id);

            // check if data toko exists
            if (!$toko) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Toko Tidak Ditemukan',
                ], 404);
            }

            // check if auth user is pengusaha
            if (Auth::user()->pengusaha) {
                // check if auth user is owner of the toko
                if (Auth::user()->pengusaha->id == $toko->pengusaha_id) {
                    // delete toko_pp
                    if ($toko->toko_pp != null) {
                        $old_toko_pp = $toko->toko_pp;
                        $old_toko_pp_path = public_path('storage/public/' . $old_toko_pp);
                        unlink($old_toko_pp_path);
                    }

                    // delete toko_bg
                    if ($toko->toko_bg != null) {
                        $old_toko_bg = $toko->toko_bg;
                        $old_toko_bg_path = public_path('storage/public/' . $old_toko_bg);
                        unlink($old_toko_bg_path);
                    }

                    // delete toko
                    $toko->delete();

                    // return response
                    return response()->json([
                        'success' => true,
                        'message' => 'Toko Berhasil Dihapus',
                    ], 200);
                } else {
                    // return response
                    return response()->json([
                        'success' => false,
                        'message' => 'User Bukan Pemilik Toko',
                    ], 401);
                }
            } else {
                // return response
                return response()->json([
                    'success' => false,
                    'message' => 'User Bukan Pengusaha',
                ], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $th->getMessage(),
            ], 500);
        }
    }

    // getTokoByPengusaha
    public function getTokoByPengusaha()
    {
        // get data toko by pengusaha
        $toko = Toko::with('produk.produk_image')->where('pengusaha_id', Auth::user()->pengusaha->id)->get();

        // return response
        return response()->json([
            'success' => true,
            'message' => 'List Data Toko',
            'data'    => $toko
        ], 200);
    }
}
