<?php

namespace App\Http\Controllers\dashboardControllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class usersController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('dashboard.users.index', compact('users'));
    }

    // إنشاء مستخدم جديد
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:6',
            // يمكنك إضافة قواعد تحقق لباقي الحقول إذا رغبت
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // إنشاء المستخدم
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'image' => $request->image,
            'status' => $request->status ?? 'active',
            'role' => $request->role ?? 'user',
            'gender' => $request->gender,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'تم انشاء المستخدم بنجاح.');
    }

    // عرض بيانات مستخدم معين
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'المستخدم غير موجود'], 404);
        }

        return redirect()->back()->with('user', $user);
    }

    // تحديث بيانات مستخدم
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'المستخدم غير موجود'], 404);
        }

        // التحقق من صحة البيانات مع استثناء المستخدم الحالي في قواعد التحقق للحقول الفريدة
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|nullable|email|unique:users,email,' . $user->id,
            'phone' => 'sometimes|required|string|unique:users,phone,' . $user->id,
            'password' => 'sometimes|required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // تحديث بيانات المستخدم
        $user->name = $request->name ?? $user->name;
        $user->email = $request->email ?? $user->email;
        $user->phone = $request->phone ?? $user->phone;
        $user->address = $request->address ?? $user->address;
        $user->image = $request->image ?? $user->image;
        $user->status = $request->status ?? $user->status;
        $user->role = $request->role ?? $user->role;
        $user->gender = $request->gender ?? $user->gender;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'تم تحديث بيانات المستخدم بنجاح.');
    }

    // حذف مستخدم
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'المستخدم غير موجود'], 404);
        }

        $user->delete();

        return redirect()->back()->with('success', 'تم حذف المستخدم بنجاح.');
    }
}
