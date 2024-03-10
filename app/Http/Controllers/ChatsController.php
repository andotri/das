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
        $messages = Message::with('user')->where('room_id', $room_id)->get();

        return $messages;
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
        $question = $request->input('question');
        $answer = $this->getAnswer($question);

        $message = $user->messages()->create([
            'room_id' => $room_id,
            'question' => $question,
            'answer' => $answer,
        ]);

        broadcast(new MessageSent($room_id, $user, $message))->toOthers();

        return [
            'answer' => $answer,
        ];
    }

    function getAnswer($question)
    {
        // Your OpenAI API key
        $OPENAI_API_KEY = env('OPENAI_API_KEY');

        // API endpoint
        $url = 'https://api.openai.com/v1/chat/completions';

        // Data to be sent in the request
        $data = array(
            "model" => "gpt-3.5-turbo",
            "messages" => array(
                array(
                    "role" => "system",
                    "content" => "Act as a startup mentor. This conversation is dedicated to discussions on startup and entrepreneurship topics. Please feel free to ask questions or share statements related to startups. If you have queries on entrepreneurship, funding, business development, or any other aspect of starting and running a business, I'm here to assist. If your question or statement seems unrelated to startups or entrepreneurship, I will kindly ask you to clarify its relevance. If you can provide a valid reason for its connection to our topic, we'll continue discussing. Otherwise, I may guide you to ask questions pertinent to startups."
                ),
                array(
                    "role" => "user",
                    "content" => $question
                )
            )
        );

        // Convert data to JSON format
        $postData = json_encode($data);

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $OPENAI_API_KEY
        ));

        // Execute cURL request
        $response = curl_exec($ch);

        // Check for errors
        if(curl_errno($ch)){
            return 'cURL error: ' . curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        // Decode JSON response
        $responseData = json_decode($response, true);

        // Accessing the content
        $content = $responseData['choices'][0]['message']['content'];
        return nl2br($content);
    }
}
