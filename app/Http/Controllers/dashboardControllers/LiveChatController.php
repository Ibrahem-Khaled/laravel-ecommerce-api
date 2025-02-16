<?php

namespace App\Http\Controllers\dashboardControllers;

use App\Http\Controllers\Controller;
use App\Models\LiveChat;
use App\Models\User;
use Illuminate\Http\Request;

class LiveChatController extends Controller
{
    public function index()
    {
        $chats = LiveChat::with(['user', 'admin'])
            ->orderBy('created_at', 'desc')
            ->get();

        $users = User::where('role', 'user')->get();

        return view('dashboard.live-chat.index', compact('chats', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        LiveChat::create([
            'user_id' => $request->user_id,
            'admin_id' => auth()->id(),
            'message' => $request->message,
            'status' => 'unread',
        ]);

        return redirect()->back()->with('success', 'تم إرسال الرسالة بنجاح');
    }

    public function updateStatus($id)
    {
        $chat = LiveChat::findOrFail($id);
        $chat->status = 'read';
        $chat->save();

        return response()->json(['success' => 'تم تحديث حالة الرسالة']);
    }

    public function getUnreadCount()
    {
        $count = LiveChat::where('status', 'unread')->count();
        return response()->json(['count' => $count]);
    }
}
