<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\MessageCreated;
use App\Models\User;
use App\Notifications\FirebaseNotification;
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
            $receiver = User::find($id);

            // nyimpen message di conversation table sender id admin, receiver user
            if ($user->role == $receiver->role) {
                return response()->json([
                    'success' => false,
                    'message' => "Can't send message to this user!",
                ], 403);
            } else {
                $conversation = $message->conversation()->create([
                    'sender_id' => $message->user_id,
                    'receiver_id' => $receiver->id
                ]);
            }
        }

        $data = [
            'user' => $user,
            'message_body' => $message,
            'conversation' => $conversation
        ];

        if($user->role == "PENGUSAHA"){
            $user_logged = $user::with('pengusaha')->first();
            $user_name = $user_logged->pengusaha->name;
        }else if($user->role == "KONSUMEN"){
            $user_logged = $user::with('konsumen')->first();
            $user_name = $user_logged->konsumen->name;
        }else{
            $user_name = "Admin";
        }

        broadcast(new MessageCreated($request->message, $user, $receiver))->toOthers();

        $notification_config = [
            "title" => "Message from " + $user_name,
            "body" => $request->message
        ];

        $this->sentNotification($notification_config, $receiver);

        return response()->json([
            'success' => true,
            'message' => 'Message Sent!',
            'data'    => $data
        ], 200);
    }

    public function sentNotification($data, $receiver)
    {
        $data = $receiver->notify(new FirebaseNotification($data['body'], $data['title']));
    }
}
