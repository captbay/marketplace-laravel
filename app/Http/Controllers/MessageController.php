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

        if ($user->role == "PENGUSAHA") {
            $user_logged = User::with('pengusaha')->find($user->id);
            $user_name = $user_logged->pengusaha->name;
        } else if ($user->role == "KONSUMEN") {
            $user_logged = User::with('konsumen')->find($user->id);
            $user_name = $user_logged->konsumen->name;
        } else {
            $user_name = "Admin";
        }

        // $notification_config = [
        //     "title" => $user_name,
        //     "body" => $request->message
        // ];

        broadcast(new MessageCreated($request->message, $user, $receiver))->toOthers();
        // $this->sentNotification($notification_config, $receiver);

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

    public function triggerNotification(Request $request, $id_receiver)
    {
        $message = $request->message;
        $user = Auth::user();

        if ($id_receiver == "admin") {
            $admin = User::where('role', "admin")->first();

            $receiver = $admin;
        } else {
            $receiver = User::find($id_receiver);
        }

        if ($user->role == "PENGUSAHA") {
            $user_logged = User::with('pengusaha')->find($user->id);
            $user_name = $user_logged->pengusaha->name;
        } else if ($user->role == "KONSUMEN") {
            $user_logged = User::with('konsumen')->find($user->id);
            $user_name = $user_logged->konsumen->name;
        } else {
            $user_name = "Admin";
        }

        $receiver->notify(new FirebaseNotification($message, $user_name));

        return response()->json([
            "success" => true,
            "message" => "Notification sent to receiver Token: ". $receiver->fcm_token,
            "data" => [
                "message_body" => $message
            ]
        ], 200);
    }
}
