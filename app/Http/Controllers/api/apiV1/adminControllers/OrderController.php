<?php

namespace App\Http\Controllers\api\apiV1\adminControllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $status = $request->query('status', null);

        if ($status) {
            $orders = Order::with(['user', 'products'])->where('status', $status)->latest()->get();
            return response()->json($orders);
        }
        // جلب الطلبات مع بيانات المستخدم وعناصر الطلب والمنتج
        $orders = Order::with(['user', 'products'])->latest()->get();
        return response()->json($orders);
    }

    /**
     * عرض تفاصيل طلب معين مع منتجاته
     */
    public function show($id)
    {
        $order = Order::with(['user', 'products'])->find($id);

        if (!$order) {
            return response()->json(['message' => 'الطلب غير موجود'], 404);
        }

        return response()->json($order);
    }

    /**
     * تغيير حالة الطلب
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'الطلب غير موجود'], 404);
        }

        // التحقق من صحة الحالة المدخلة
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:in_cart,pending,processing,delivered,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // تحديث الحالة
        $order->status = $request->status;
        $order->save();

        return response()->json($order);
    }
}
