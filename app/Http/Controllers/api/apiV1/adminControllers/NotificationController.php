<?php

namespace App\Http\Controllers\api\apiV1\adminControllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('user')->latest()->get();
        return response()->json($notifications);
    }

    // إنشاء إشعار جديد مع رفع الصورة وتخزين الرابط في قاعدة البيانات
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المدخلة مع التحقق من الملف كصورة
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // معالجة رفع الصورة في حال وجودها
        $imageUrl = null;
        if ($request->hasFile('image')) {
            // تخزين الصورة في مجلد uploads/notifications ضمن القرص public
            $imagePath = $request->file('image')->store('uploads/notifications', 'public');
            // إنشاء رابط URL للصورة
            $imageUrl = asset('storage/' . $imagePath);
        }

        // إنشاء الإشعار مع حفظ رابط الصورة
        $notification = Notification::create([
            'title' => $request->title,
            'message' => $request->message,
            'image' => $imageUrl,
            'user_id' => $request->user_id,
        ]);

        return response()->json($notification, 201);
    }

    // عرض بيانات إشعار معين
    public function show($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json(['message' => 'الإشعار غير موجود'], 404);
        }

        return response()->json($notification);
    }

    // تحديث بيانات إشعار مع إمكانية تحديث الصورة
    public function update(Request $request, $id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json(['message' => 'الإشعار غير موجود'], 404);
        }

        // التحقق من صحة البيانات المرسلة
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'message' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // معالجة رفع الصورة في حال وجود ملف جديد
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/notifications', 'public');
            $imageUrl = asset('storage/' . $imagePath);
            $notification->image = $imageUrl;
        }

        // تحديث باقي الحقول
        if ($request->has('title')) {
            $notification->title = $request->title;
        }
        if ($request->has('message')) {
            $notification->message = $request->message;
        }
        if ($request->has('user_id')) {
            $notification->user_id = $request->user_id;
        }

        $notification->save();

        return response()->json($notification);
    }

    // حذف إشعار
    public function destroy($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json(['message' => 'الإشعار غير موجود'], 404);
        }

        $notification->delete();

        return response()->json(['message' => 'تم حذف الإشعار بنجاح']);
    }
}
