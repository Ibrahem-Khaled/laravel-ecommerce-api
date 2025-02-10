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
            'phone' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }
        // إنشاء المستخدم
        $user = User::create([
            'name' => $request->name,
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
        $credentials = $request->only('phone', 'password');

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


    public function update(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string',
            'address' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gender' => 'nullable|string|in:male,female,other',
        ]);

        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $user->image = $imageName;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->gender = $request->gender;
        $user->save();

        return response()->json([
            'message' => 'تم تحديث بيانات المستخدم بنجاح',
            'user' => $user,
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

    public function changePassword(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $validatedData = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6',
        ]);

        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['error' => 'كلمة المرور القديمة غير صحيحة'], 401);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return response()->json(['message' => 'تم تغيير كلمة المرور بنجاح']);
    }

    public function delete()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->delete();
        return response()->json(['message' => 'تم حذف المستخدم بنجاح']);
    }
}