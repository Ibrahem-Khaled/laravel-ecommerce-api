<?php

namespace App\Http\Controllers\api\apiV1\customerController;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class LiveChatController extends Controller
{
    public function index()
    {
        $user = auth()->guard('api')->user();
        $chats = $user->chats()->get();
        return response()->json($chats);
    }

    public function store(Request $request)
    {
        $user = auth()->guard('api')->user();
        $admin = User::where('role', 'admin')->first();
        $chat = $user->chats()->create([
            'message' => $request->message
        ]);
        if ($user->chats()->count() == 1) {
            $user->chats()->create([
                'message' => 'مرحبا بك في كيف يمكننا مساعدتك',
                'admin_id' => $admin->id,
            ]);
        }
        return response()->json($chat);
    }
}
