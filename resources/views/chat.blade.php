<!-- resources/views/chat.blade.php -->

@extends('layouts.app')

@section('content')
<div class="iframe-section">
    <!-- Your iframe content goes here -->
    <iframe src="https://miro.com/app/live-embed/uXjVNb_gEdc="></iframe>
</div>
<br>
<div class="container">
    <div class="chatbot-section">
        <div class="card">
            <div class="card-header"><strong>Your own startup assistant - Room {{ $room_id }}</strong></div>
            <input type="hidden" id="room_id" name="room_id" value="{{ $room_id }}">
            <div class="card-body">
                <chat-messages :messages="messages"></chat-messages>
            </div>
            <div class="card-footer">
                <chat-form v-on:messagesent="addMessage" :user="{{ Auth::user() }}"></chat-form>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .iframe-section {
        width: 100%; /* Set the width to 100% */
    }

    .iframe-section iframe {
        width: 100%; /* Make the iframe fill the entire width of its container */
        height: 65%; /* Set the height to fill the container as well */
        border: none; /* Remove default iframe border */
    }
</style>
