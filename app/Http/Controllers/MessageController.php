<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\MessageCreated;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{

    // user sent message to admin
    public function sentMessage(Request $request, $id)
    {
        $user = Auth::user();

        $message = $user->messages()->create([
            'message' => $request->message
        ]);

        if ($id == "admin") {
            $admin = User::where('role', "admin")->first();
            // nyimpen message di conversation table sender id user, receiver id admin
            $conversation = $message->conversation()->create([
                'sender_id' => $message->user_id,
                'receiver_id' => $admin->id
            ]);

            $receiver = $admin;
            
        } else {
            // nyimpen message di conversation table sender id admin, receiver user
            if($user->role == 'KONSUMEN' || $user->role == 'PENGUSAHA'){
                return response()->json([
                    'success' => false,
                    'message' => "Can't send message to another user!",
                ], 403);
            }else{
                $conversation = $message->conversation()->create([
                    'sender_id' => $message->user_id,
                    'receiver_id' => $id
                ]);

                $receiver = User::find($id);
            }

        }

        $data = [
            'user' => $user,
            'message_body' => $message,
            'conversation' => $conversation
        ];

        broadcast(new MessageCreated($request->message, $user, $receiver))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'Message Sent!',
            'data'    => $data
        ], 200);
    }
}
