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
        // جلب جميع المستخدمين (يمكنك تعديل الاستعلام لاستثناء بعض الحسابات مثل المشرف)
        $users = User::all();
        return view('dashboard.live-chat.index', compact('users'));
    }

    // عرض دردشة المستخدم المحدد
    public function showUserChat($userId)
    {
        $selectedUser = User::findOrFail($userId);

        // جلب الرسائل الخاصة بالمستخدم المحدد.
        // نفترض أن الرسائل محفوظة بحيث يكون الحقل user_id للمستخدم المستهدف،
        // وحقل admin_id للمشرف الذي يرد على الرسالة.
        $messages = LiveChat::with('user')
            ->where(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->orWhere('admin_id', $userId);
            })->orderBy('created_at', 'asc')->get();

        return view('dashboard.live-chat.user-chat', compact('messages', 'selectedUser'));
    }

    // جلب الرسائل الخاصة بالمستخدم المحدد (AJAX)
    public function fetchUserChatMessages($userId)
    {
        $messages = LiveChat::with('user')
            ->where(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->orWhere('admin_id', $userId);
            })->orderBy('created_at', 'asc')->get();

        return response()->json($messages);
    }

    // إرسال رسالة للمستخدم المحدد (AJAX)
    public function sendMessage(Request $request, $userId)
    {
        $request->validate([
            'message' => 'required'
        ]);

        $liveChat = new LiveChat();
        // نفترض أن الرسالة ترسل من قبل المشرف الحالي
        // حيث يكون user_id هو معرف المستخدم المستهدف
        // وadmin_id هو معرف المشرف الحالي
        $liveChat->user_id = $userId;
        $liveChat->admin_id = auth()->user()->id;
        $liveChat->message = $request->message;
        $liveChat->save();

        return redirect()->back();
        }

}