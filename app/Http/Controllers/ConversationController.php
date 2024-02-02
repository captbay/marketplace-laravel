<?php

namespace App\Http\Controllers;

use App\Models\Conversations;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConversationController extends Controller
{
    public function getConversationByUserId($id)
    {
        $user = Auth::user();

        if ($id == "admin") {
            $admin = User::where('role', "admin")->first();
            $receiver_id = $admin->id;
        } else {
            $receiver_id = $id;
        }

        // $conversation = Conversations::where('sender_id', $user->id)->where('receiver_id', $receiver_id)->with('messages')->get();
        $conversation = Conversations::where(function ($query) use ($user, $receiver_id) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', $receiver_id);
        })->orWhere(function ($query) use ($user, $receiver_id) {
            $query->where('sender_id', $receiver_id)
                ->where('receiver_id', $user->id);
        })->with('messages')->get();

        return response()->json([
            'success' => true,
            'data' => $conversation
        ], 200);
    }

    public function getAllConversations()
    {
        $admin = User::where('role', '=', 'admin')->first();
        $user = Auth::user();
        $userId = $user->id;

        $conversation = Conversations::whereIn('id', function ($query) use ($userId, $admin) {
            $query->select(DB::raw('MAX(id)'))
                ->from('conversations')
                ->where(function ($query) use ($userId, $admin) {
                    $query->where('sender_id', $userId)
                        ->where('receiver_id', '!=', $admin->id);
                })->orWhere(function ($query) use ($userId, $admin) {
                    $query->where('sender_id', '!=', $admin->id)
                        ->where('receiver_id', $userId);
                })
                ->groupBy(DB::raw('IF(sender_id = ' . $userId . ', receiver_id, sender_id)'));
        })
            ->orderBy('created_at', 'desc')->with('messages', 'sender', 'receiver')
            ->get();

        return response()->json([
            'user' => $user,
            'conversation' => $conversation
        ]);
    }
}
