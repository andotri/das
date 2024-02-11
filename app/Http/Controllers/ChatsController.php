<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ChatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show chats
     *
     * @return \Illuminate\Http\Response
     */
    public function show($room_id)
    {
        return view('chat', [
            'room_id' => $room_id,
        ]);
    }

    /**
     * Fetch all messages
     *
     * @return Message
     */
    public function fetchMessages($room_id)
    {
        return Message::with('user')->where('room_id', $room_id)->get();
    }

    /**
     * Persist message to database
     *
     * @param  Request $request
     * @return Response
     */
    public function sendMessage(Request $request)
    {
        $user = Auth::user();
        $room_id = $request->input('room_id');
        $message = $request->input('message');

        $message = $user->messages()->create([
            'room_id' => $room_id,
            'message' => $message
        ]);

        broadcast(new MessageSent($room_id, $user, $message))->toOthers();

        return ['status' => 'Message Sent!'];
    }
}
