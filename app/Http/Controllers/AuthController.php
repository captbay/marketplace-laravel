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
                'fcm_token' => 'required'
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
                    $user->fcm_token = $request->fcm_token;
                    $user->save();

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
                    $user->fcm_token = $request->fcm_token;
                    $user->save();

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
                } else if ($user->role == 'admin') {
                    $user->fcm_token = $request->fcm_token;
                    $user->save();

                    return response()->json([
                        'data' => null,
                        'email' => $user->email,
                        'message' => 'Authenticated as a admin active',
                        'role' => 'admin',
                        'token_type' => 'Bearer',
                        'access_token' => $token
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Not eligible to login',
                    ], 404);
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
                'fcm_token' => 'required'
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
                    'fcm_token' => $request->fcm_token
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
                    'fcm_token' => $request->fcm_token
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
        $user = Auth::user();
        $userLogout = User::find($user->id);

        $userLogout->fcm_token = null;
        $userLogout->save();
        
        $user->currentAccessToken()->delete();

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

    // uploadProfilePicture
    public function uploadProfilePicture(Request $request)
    {
        try {
            $validatedData = Validator::make($request->all(), [
                'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            //response error validation
            if ($validatedData->fails()) {
                return response()->json(['message' => $validatedData->errors()], 422);
            }

            $user = Auth::user();

            switch ($user->role) {
                case 'KONSUMEN':
                    $data = $user->konsumen;
                    $message = 'Berhasil Upload Profile Picture KONSUMEN';
                    break;

                case 'PENGUSAHA':
                    $data = $user->pengusaha;
                    $message = ' Berhasil Upload Profile Picture PENGUSAHA';
                    break;

                default:
                    $data = null;
                    $message = 'Unknown role';
                    break;
            }

            // if profile_picture is not null
            if ($data->profile_picture != null) {
                // delete old profile_picture
                unlink(public_path('storage/public/' . $data->profile_picture));
            }

            // upload new profile_picture
            $profile_picture = $request->file('profile_picture');
            $profile_picture_name = time() . '_' . $profile_picture->getClientOriginalName();
            $profile_picture->storeAs('public/profile_picture', $profile_picture_name);

            $data->update([
                'profile_picture' => 'profile_picture/' . $profile_picture_name,
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

    // deleteProfilePicture
    public function deleteProfilePicture()
    {
        try {
            $user = Auth::user();

            switch ($user->role) {
                case 'KONSUMEN':
                    $data = $user->konsumen;
                    $message = 'Berhasil Delete Profile Picture KONSUMEN';
                    break;

                case 'PENGUSAHA':
                    $data = $user->pengusaha;
                    $message = ' Berhasil Delete Profile Picture PENGUSAHA';
                    break;

                default:
                    $data = null;
                    $message = 'Unknown role';
                    break;
            }

            // if profile_picture is not null
            if ($data->profile_picture != null) {
                // delete old profile_picture
                unlink(public_path('storage/public/' . $data->profile_picture));

                $data->update([
                    'profile_picture' => null,
                ]);
            }

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

    // uploadBackgroundPicture
    public function uploadBackgroundPicture(Request $request)
    {
        try {
            $validatedData = Validator::make($request->all(), [
                'background_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            //response error validation
            if ($validatedData->fails()) {
                return response()->json(['message' => $validatedData->errors()], 422);
            }

            $user = Auth::user();

            switch ($user->role) {
                case 'KONSUMEN':
                    $data = $user->konsumen;
                    $message = 'Berhasil Upload Background Picture KONSUMEN';
                    break;

                case 'PENGUSAHA':
                    $data = $user->pengusaha;
                    $message = ' Berhasil Upload Background Picture PENGUSAHA';
                    break;

                default:
                    $data = null;
                    $message = 'Unknown role';
                    break;
            }

            // if background_picture is not null
            if ($data->background_picture != null) {
                // delete old background_picture
                unlink(public_path('storage/public/' . $data->background_picture));
            }

            // upload new background_picture
            $background_picture = $request->file('background_picture');
            $background_picture_name = time() . '_' . $background_picture->getClientOriginalName();
            $background_picture->storeAs('public/background_picture', $background_picture_name);

            $data->update([
                'background_picture' => 'background_picture/' . $background_picture_name,
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

    // deleteBackgroundPicture
    public function deleteBackgroundPicture()
    {
        try {
            $user = Auth::user();

            switch ($user->role) {
                case 'KONSUMEN':
                    $data = $user->konsumen;
                    $message = 'Berhasil Delete Background Picture KONSUMEN';
                    break;

                case 'PENGUSAHA':
                    $data = $user->pengusaha;
                    $message = ' Berhasil Delete Background Picture PENGUSAHA';
                    break;

                default:
                    $data = null;
                    $message = 'Unknown role';
                    break;
            }

            // if background_picture is not null
            if ($data->background_picture != null) {
                // delete old background_picture
                unlink(public_path('storage/public/' . $data->background_picture));

                $data->update([
                    'background_picture' => null,
                ]);
            }

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

    // destroy
    public function destroy($id)
    {
        try {
            $user = User::find($id);

            // if user is not null
            if ($user != null) {
                // if profile_picture is not null
                if ($user->profile_picture != null) {
                    // delete old profile_picture
                    unlink(public_path('storage/public/' . $user->profile_picture));
                }

                // if background_picture is not null
                if ($user->background_picture != null) {
                    // delete old background_picture
                    unlink(public_path('storage/public/' . $user->background_picture));
                }

                $user->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'User deleted successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'User failed to delete',
                ], 409);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
