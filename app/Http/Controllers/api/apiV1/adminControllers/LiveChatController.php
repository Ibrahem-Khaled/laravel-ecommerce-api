<?php

namespace App\Http\Controllers\api\apiV1\adminControllers;

use App\Http\Controllers\Controller;
use App\Models\LiveChat;
use App\Models\User;
use Illuminate\Http\Request;

class LiveChatController extends Controller
{
    public function index()
    {
        $chats = User::with('chats')->latest()->get();
        return response()->json($chats);
    }

    public function replay(Request $request, $chatId)
    {
        $user = auth()->guard('api')->user();
        $chat = LiveChat::find($chatId);

        LiveChat::create([
            'user_id' => $chat->user_id,
            'admin_id' => $user->id,
            'message' => $request->message,
        ]);

        return response()->json($chat);
    }

    public function destroy($chatId)
    {
        $chat = LiveChat::find($chatId);
        $chat->delete();
        return response()->json(['message' => 'تم حذف الرسالة بنجاح']);
    }
}
