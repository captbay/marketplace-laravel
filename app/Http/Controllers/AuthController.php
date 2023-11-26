<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Login a user and create a new token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        try {
            $validatedData = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required',
            ]);

            //response error validation
            if ($validatedData->fails()) {
                return response()->json(['message' => $validatedData->errors()], 422);
            }

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password false or user Not Found',
                ], 404);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            // if password is correct
            if (Hash::check($request->password, $user->password, [])) {
                // check role
                if ($user->role == 'KONSUMEN') {
                    // get data KONSUMEN
                    $data = $user->konsumen;

                    // set id to konsumen_id
                    $data->konsumen_id = $data->id;

                    // unset id
                    unset($data->id);

                    return response()->json([
                        'data' => $data,
                        'email' => $user->email,
                        'message' => 'Authenticated as a KONSUMEN active',
                        'role' => 'KONSUMEN',
                        'token_type' => 'Bearer',
                        'access_token' => $token
                    ], 200);
                } else if ($user->role == 'PENGUSAHA') {
                    // get data PENGUSAHA
                    $data = $user->pengusaha;

                    // set id to pengusaha
                    $data->pengusaha_id = $data->id;

                    // unset id
                    unset($data->id);

                    // return
                    return response()->json([
                        'data' => $data,
                        'email' => $user->email,
                        'message' => 'Authenticated as a PENGUSAHA active',
                        'role' => 'PENGUSAHA',
                        'token_type' => 'Bearer',
                        'access_token' => $token
                    ], 200);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Password false or user Not Found',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        try {
            $validatedData = Validator::make($request->all(), [
                'email' => 'required|email|unique:users',
                'password' => 'required|string',
                'name' => 'required|string',
                'phone_number' => 'required|regex:/^(0)8[1-9][0-9]{6,10}$/',
                'address' => 'required|string',
                'gender' => 'required|string|in:MALE,FEMALE,RATHER NOT SAY',
                'role' => 'required|string|in:KONSUMEN,PENGUSAHA',
            ]);

            //response error validation
            if ($validatedData->fails()) {
                return response()->json(['message' => $validatedData->errors()], 422);
            }

            // if role konsumen
            if ($request->role == "KONSUMEN") {
                // create user konsumen
                $user = User::create([
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => $request->role,
                ]);

                // create konsumen
                $user->konsumen()->create([
                    'name' => $request->name,
                    'phone_number' => $request->phone_number,
                    'address' => $request->address,
                    'gender' => $request->gender,
                ]);

                // return
                return response()->json([
                    'success' => true,
                    'message' => 'Register as konsumen successfully',
                ], 200);
            } else if ($request->role == "PENGUSAHA") {
                // create user pengusaha
                $user = User::create([
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => $request->role,
                ]);

                // create pengusaha
                $user->pengusaha()->create([
                    'name' => $request->name,
                    'phone_number' => $request->phone_number,
                    'address' => $request->address,
                    'gender' => $request->gender,
                ]);

                // return
                return response()->json([
                    'success' => true,
                    'message' => 'Register as pengusaha successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Not eligible to register',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    // change password
    public function changePassword(Request $request)
    {
        try {
            $validatedData = Validator::make($request->all(), [
                'old_password' => 'required',
                'new_password' => 'required',
                'new_confirm_password' => 'required|same:new_password',
            ]);

            //response error validation
            if ($validatedData->fails()) {
                return response()->json(['message' => $validatedData->errors()], 422);
            }

            $user = User::find(Auth::user()->id);

            // if old password is not correct
            if (!Hash::check($request->old_password, $user->password, [])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Old password is not correct',
                ], 404);
            }

            // if new_password same as old_password
            if (Hash::check($request->new_password, $user->password, [])) {
                return response()->json([
                    'success' => false,
                    'message' => 'New password cannot be the same as old password',
                ], 404);
            }

            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            if ($user) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password changed successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Password failed to change',
                ], 409);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Logout a user (revoke the token).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out',
        ], 200);
    }

    public function userLogin()
    {
        try {
            $user = Auth::user();

            switch ($user->role) {
                case 'KONSUMEN':
                    $data = $user->konsumen;

                    // set id to konsumen_id
                    $data->konsumen_id = $data->id;

                    // unset id
                    unset($data->id);

                    $message = 'Profile KONSUMEN';
                    break;

                case 'PENGUSAHA':
                    $data = $user->pengusaha;

                    // set id to pengusaha
                    $data->pengusaha_id = $data->id;

                    // unset id
                    unset($data->id);

                    $message = ' Profile PENGUSAHA';
                    break;

                default:
                    $data = null;
                    $message = 'Unknown role';
                    break;
            }

            return response()->json([
                'data' => $data,
                'email' => $user->email,
                'message' => $message,
                'role' => $user->role,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    // updateUserLogin
    public function updateUserLogin(Request $request)
    {
        try {
            $validatedData = Validator::make($request->all(), [
                'name' => 'required|string',
                'phone_number' => 'required|regex:/^(0)8[1-9][0-9]{6,10}$/',
                'address' => 'required|string',
                'gender' => 'required|string|in:MALE,FEMALE,RATHER NOT SAY',
            ]);

            //response error validation
            if ($validatedData->fails()) {
                return response()->json(['message' => $validatedData->errors()], 422);
            }

            $user = Auth::user();

            switch ($user->role) {
                case 'KONSUMEN':
                    $data = $user->konsumen;
                    $message = 'Berhasil Edit Data KONSUMEN';
                    break;

                case 'PENGUSAHA':
                    $data = $user->pengusaha;
                    $message = ' Berhasil Edit Data PENGUSAHA';
                    break;

                default:
                    $data = null;
                    $message = 'Unknown role';
                    break;
            }

            $data->update([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'gender' => $request->gender,
            ]);

            return response()->json([
                'data' => $data,
                'message' => $message
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
