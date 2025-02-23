<?php

namespace App\Http\Controllers\dashboardControllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::all();
        $subCategories = SubCategory::all();
        $users = User::all();
        return view('dashboard.products.index', compact('products', 'subCategories', 'users'));
    }

    // إنشاء منتج جديد مع دعم رفع الصور
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $validator = Validator::make($request->all(), [
            'sub_category_id' => 'required|exists:sub_categories,id',
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
            // إذا كانت الصور مرفوعة كملفات، يتم التحقق من كل ملف
            'images' => 'nullable',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'views' => 'nullable|integer',
            'type' => 'nullable|in:basic,hot,new,special',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $imagesJson = null;
        // معالجة رفع الصور إذا كانت مرفقة كملفات
        if ($request->hasFile('images')) {
            $imagesUrls = [];
            foreach ($request->file('images') as $file) {
                $path = $file->store('uploads/products', 'public');
                $imagesUrls[] = asset('storage/' . $path);
            }
            $imagesJson = json_encode($imagesUrls);
        } elseif ($request->images) {
            // في حال إرسال بيانات الصور كـ JSON (مثلاً روابط مباشرة)
            $imagesJson = $request->images;
        }

        // إنشاء المنتج مع حفظ روابط الصور في حقل images
        $product = Product::create([
            'sub_category_id' => $request->sub_category_id,
            'user_id' => $request->user_id,
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status ?? 'active',
            'images' => $imagesJson,
            'price' => $request->price,
            'price_after_discount' => $request->price_after_discount,
            'quantity' => $request->quantity,
            'views' => $request->views ?? 0,
            'type' => $request->type,
        ]);

        return redirect()->route('products.index')->with('success', 'تم انشاء المنتج بنجاح');
    }

    // عرض بيانات منتج معين
    public function show($id)
    {
        $product = Product::find($id);
        $product->load('subCategory', 'user');
        $product->images = json_decode($product->images);
        if (!$product) {
            return response()->json(['message' => 'المنتج غير موجود'], 404);
        }

        return response()->json($product);
    }

    // تحديث بيانات منتج مع دعم تحديث الصور
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'المنتج غير موجود'], 404);
        }

        // التحقق من صحة البيانات مع السماح بتحديث بعض الحقول
        $validator = Validator::make($request->all(), [
            'sub_category_id' => 'sometimes|required|exists:sub_categories,id',
            'user_id' => 'sometimes|required|exists:users,id',
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
            'images' => 'nullable',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'price' => 'sometimes|required|numeric',
            'quantity' => 'sometimes|required|integer',
            'views' => 'nullable|integer',
            'type' => 'nullable|in:basic,hot,new,special',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // معالجة تحديث الصور إذا تم رفع ملفات جديدة
        if ($request->hasFile('images')) {
            $imagesUrls = [];
            foreach ($request->file('images') as $file) {
                $path = $file->store('uploads/products', 'public');
                $imagesUrls[] = asset('storage/' . $path);
            }
            $product->images = json_encode($imagesUrls);
        } elseif ($request->has('images')) {
            // تحديث الصور إذا تم إرسالها كبيانات (JSON)
            $product->images = $request->images;
        }

        // تحديث باقي الحقول
        $product->sub_category_id = $request->sub_category_id ?? $product->sub_category_id;
        $product->user_id = $request->user_id ?? $product->user_id;
        $product->name = $request->name ?? $product->name;
        $product->description = $request->description ?? $product->description;
        $product->status = $request->status ?? $product->status;
        $product->price = $request->price ?? $product->price;
        $product->price_after_discount = $request->price_after_discount ?? $product->price_after_discount;
        $product->quantity = $request->quantity ?? $product->quantity;
        $product->views = $request->views ?? $product->views;
        $product->type = $request->type ?? $product->type;
        $product->save();

        return redirect()->route('products.index')->with('success', 'تم تحديث المنتج بنجاح');
    }

    // حذف منتج
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'المنتج غير موجود'], 404);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'تم حذف المنتج بنجاح');
    }
}
