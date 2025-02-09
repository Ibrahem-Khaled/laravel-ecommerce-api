<?php

namespace App\Http\Controllers\api\apiV1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class authController extends Controller
{
    public function register(Request $request)
    {
        // التحقق من صحة البيانات
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }
        // إنشاء المستخدم
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password), // تشفير كلمة المرور
        ]);

        // إنشاء token للمستخدم الجديد
        $token = JWTAuth::fromUser($user);

        // إرجاع الاستجابة مع token وبيانات المستخدم
        return response()->json([
            'message' => 'تم إنشاء الحساب بنجاح',
            'user' => $user,
            'token' => $token,
        ], 201); // 201: Created
    }
    /**
     * تسجيل الدخول وإنشاء token
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'بيانات الاعتماد غير صحيحة'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'لا يمكن إنشاء token'], 500);
        }

        return response()->json([
            'token' => $token,
            'user' => Auth::user(),
        ]);
    }

    /**
     * جلب بيانات المستخدم الحالي
     */
    public function user(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json(['user' => $user]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'غير مصرح به'], 401);
        }
    }

    /**
     * تسجيل الخروج وإبطال token
     */
    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'تم تسجيل الخروج بنجاح']);
        } catch (JWTException $e) {
            return response()->json(['error' => 'فشل تسجيل الخروج'], 500);
        }
    }
}