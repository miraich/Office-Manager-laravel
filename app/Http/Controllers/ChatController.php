<?php

namespace App\Http\Controllers;

use App\Events\GotMessage;
use App\Http\Requests\MessageFormRequest;
use App\Jobs\SendMessage;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function index()
    {

    }

    public function store(Request $request)
    {
        $chat = Chat::create([
            'creator_id' => $request->user()->id,
            'name' => Str::random(),
        ]);
        return response()->json($chat,201);
    }

    public function show(): JsonResponse //показ сообщений определенного чата
    {
        $messages = Message::with('user')->get();
        return response()->json($messages);
    }

}
