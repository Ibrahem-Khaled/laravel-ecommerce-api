<?php

namespace App\Http\Controllers\api\apiV1\adminControllers;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subCategories = SubCategory::all();
        return response()->json($subCategories);
    }

    // إنشاء تصنيف فرعي جديد مع دعم رفع الصورة وتخزين الرابط في قاعدة البيانات
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المدخلة مع التحقق من الملف كصورة
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // التأكد من أن الملف صورة وبامتدادات محددة
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // معالجة رفع الصورة في حال وجودها
        $imageUrl = null;
        if ($request->hasFile('image')) {
            // تخزين الصورة في مجلد uploads/subcategories ضمن القرص public
            $imagePath = $request->file('image')->store('uploads/subcategories', 'public');
            // إنشاء رابط URL للصورة
            $imageUrl = asset('storage/' . $imagePath);
        } else {
            // في حال إرسال رابط للصورة مباشرة
            $imageUrl = $request->image;
        }

        // إنشاء التصنيف الفرعي وتخزين بيانات الصورة
        $subCategory = SubCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status ?? 'active',
            'image' => $imageUrl,
            'category_id' => $request->category_id,
        ]);

        return response()->json($subCategory, 201);
    }

    // عرض بيانات تصنيف فرعي معين
    public function show($id)
    {
        $subCategory = SubCategory::find($id);

        if (!$subCategory) {
            return response()->json(['message' => 'التصنيف الفرعي غير موجود'], 404);
        }

        return response()->json($subCategory);
    }

    // تحديث بيانات تصنيف فرعي مع دعم تحديث الصورة
    public function update(Request $request, $id)
    {
        $subCategory = SubCategory::find($id);

        if (!$subCategory) {
            return response()->json(['message' => 'التصنيف الفرعي غير موجود'], 404);
        }

        // التحقق من صحة البيانات مع السماح بتحديث بعض الحقول
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'sometimes|required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // معالجة رفع الصورة إذا تم إرسال ملف جديد
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/subcategories', 'public');
            $imageUrl = asset('storage/' . $imagePath);
            $subCategory->image = $imageUrl;
        } elseif ($request->has('image')) {
            // تحديث الصورة في حال إرسال رابط مباشر للصورة
            $subCategory->image = $request->image;
        }

        // تحديث باقي الحقول
        $subCategory->name = $request->name ?? $subCategory->name;
        $subCategory->description = $request->description ?? $subCategory->description;
        $subCategory->status = $request->status ?? $subCategory->status;
        $subCategory->category_id = $request->category_id ?? $subCategory->category_id;
        $subCategory->save();

        return response()->json($subCategory);
    }

    // حذف تصنيف فرعي
    public function destroy($id)
    {
        $subCategory = SubCategory::find($id);

        if (!$subCategory) {
            return response()->json(['message' => 'التصنيف الفرعي غير موجود'], 404);
        }

        $subCategory->delete();

        return response()->json(['message' => 'تم حذف التصنيف الفرعي بنجاح']);
    }
}
