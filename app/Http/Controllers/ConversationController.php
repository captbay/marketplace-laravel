<?php

namespace App\Http\Controllers;

use App\Models\Conversations;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function getConversationByUserId($id){
        $user = Auth::user();

        if($id == "admin"){
            $admin = User::where('role', "admin")->first();
            $receiver_id = $admin->id;
        }else{
            $receiver_id = $id;
        }

        // $conversation = Conversations::where('sender_id', $user->id)->where('receiver_id', $receiver_id)->with('messages')->get();
        $conversation = Conversations::where(function($query) use ($user, $receiver_id) {
            $query->where('sender_id', $user->id)
                    ->where('receiver_id', $receiver_id);
        })->orWhere(function($query) use ($user, $receiver_id) {
            $query->where('sender_id', $receiver_id)
                    ->where('receiver_id', $user->id);
        })->with('messages')->get();

        return response()->json([
            'success' => true,
            'data' => $conversation
        ], 200);
    }
}
