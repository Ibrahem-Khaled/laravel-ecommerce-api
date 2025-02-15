<?php

namespace App\Http\Controllers\api\apiV1\customerController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LiveChatController extends Controller
{
    public function index()
    {
        $user = auth()->guard('api')->user();
        $chats = $user->chats()->latest()->get();
        return response()->json($chats);
    }

    public function store(Request $request)
    {
        $user = auth()->guard('api')->user();
        $chat = $user->chats()->create([
            'message' => $request->message
        ]);
        if ($user->chats()->count() == 1) {
            $user->chats()->create([
                'message' => 'مرحبا بك في كيف يمكننا مساعدتك'
            ]);
        }
        return response()->json($chat);
    }
}
