<?php

namespace App\Http\Controllers\dashboardControllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('dashboard.categories.index', compact('categories'));
    }

    // إنشاء فئة جديدة مع رفع الصورة وتخزين الرابط في قاعدة البيانات
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المدخلة مع التحقق من صحة الملف كصورة
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048', // تحقق من أن الملف صورة وبامتدادات محددة
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // معالجة رفع الصورة في حال وجودها
        $imageUrl = null;
        if ($request->hasFile('image')) {
            // تخزين الصورة في مجلد uploads/categories ضمن القرص public
            $imagePath = $request->file('image')->store('uploads/categories', 'public');
            // إنشاء رابط للصورة
            $imageUrl = asset('storage/' . $imagePath);
        }

        // إنشاء الفئة مع حفظ رابط الصورة في الحقل image
        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status ?? 'active',
            'image' => $imageUrl,
        ]);

        return redirect()->route('categories.index')->with('success', 'تم انشاء الفئة بنجاح');
    }

    // عرض بيانات فئة معينة
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'الفئة غير موجودة'], 404);
        }

        return redirect()->route('categories.index')->with('category', $category);
    }

    // تحديث بيانات فئة مع إمكانية تحديث الصورة
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'الفئة غير موجودة'], 404);
        }

        // التحقق من صحة البيانات مع السماح بتحديث بعض الحقول بما في ذلك الصورة
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // تحديث الصورة إذا تم رفع ملف جديد
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/categories', 'public');
            $imageUrl = asset('storage/' . $imagePath);
            $category->image = $imageUrl;
        }

        // تحديث باقي الحقول
        $category->name = $request->name ?? $category->name;
        $category->description = $request->description ?? $category->description;
        $category->status = $request->status ?? $category->status;
        $category->save();

        return redirect()->route('categories.index')->with('success', 'تم تحديث الفئة بنجاح');
    }

    // حذف فئة
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'الفئة غير موجودة'], 404);
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'تم حذف الفئة بنجاح');
    }
}
