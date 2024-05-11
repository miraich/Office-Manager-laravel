<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageFormRequest;
use App\Jobs\SendMessage;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    public function store(MessageFormRequest $request): JsonResponse
    {

        $message = $request->user()->messages()->create([
            'text' => $request->get('text'),
            'chat_id' => $chat->id,
        ]);

        SendMessage::dispatch($message);
        return response()->json(['message' => $message], 201);
    }
}
