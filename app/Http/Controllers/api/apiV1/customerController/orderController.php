<?php

namespace App\Http\Controllers\api\apiV1\customerController;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function cart()
    {
        $user = auth()->guard('api')->user();

        $order = $user->orders()->where('status', 'in_cart')->latest()->first();
        if (!$order) {
            return response()->json([
                'success' => false,
                'orders' => [],
                'message' => 'لم تقم بطلب منتجات'
            ], 404);
        }

        $order->load('products');
        return response()->json([
            'success' => true,
            'orders' => $order,
            'order_count' => $order->products()->count(),
            // 'total_price' => $order->products()->sum('price')
        ], 200);
    }

    public function checkout(Request $request)
    {
        $user = auth()->guard('api')->user();
        $appSettings = AppSettings::first();

        $order = Order::where('user_id', $user->id)->where('status', 'in_cart')->first();
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'لم تقم بطلب منتجات'
            ], 404);
        }

        if ($request->coupon) {
            Coupon::where('code', $request->coupon)->update([
                'is_used' => true,
                'user_id' => $user->id,
                'order_id' => $order->id,
            ]);
        }

        $order->status = 'pending';
        $order->shipping_cost = $appSettings->shipping_cost ?? 10;
        $order->tax = $appSettings->tax ?? 10;

        $order->lat = $request->lat;
        $order->lng = $request->lng;

        $order->save();

        return response()->json([
            'success' => true,
            'order' => $order
        ], 200);
    }

    public function userOrders()
    {
        $user = auth()->guard('api')->user();
        $orders = $user->orders()
            ->with('user')
            ->where('status', '!=', 'in_cart')->latest()->get();
        return response()->json([
            'success' => true,
            'orders' => $orders
        ], 200);
    }

    public function orderDetails($id)
    {
        $user = auth()->guard('api')->user();
        $order = $user->orders()->where('status', '!=', 'in_cart')->where('id', $id)
            ->with('products', 'user')
            ->first();

        return response()->json([
            'success' => true,
            'order' => $order
        ], 200);
    }

    public function addProduct(Request $request)
    {
        $user = auth()->guard('api')->user();

        $order = Order::firstOrCreate([
            'user_id' => $user->id,
            'status' => 'in_cart'
        ]);

        $productId = $request->product_id;
        $quantity = $request->quantity;

        // التحقق مما إذا كان المنتج موجودًا
        $product = Product::find($productId);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'المنتج غير موجود في قاعدة البيانات'
            ], 404);
        }

        $price = $product->price_after_discount > 0 ? $product->price_after_discount : $product->price * $quantity;

        // إضافة المنتج للطلب
        $order->products()->syncWithoutDetaching([
            $productId => [
                'quantity' => $quantity,
                'price' => $price,
            ]
        ]);

        return response()->json([
            'success' => true,
            'order' => $order->load('products') // تحميل المنتجات المرتبطة
        ], 200);
    }

    public function removeProduct(Request $request)
    {
        $user = auth()->guard('api')->user();
        $order = Order::where('user_id', $user->id)->where('status', 'in_cart')->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        }

        $order->products()->detach($request->product_id);

        return response()->json([
            'success' => true,
            'order' => $order
        ], 200);
    }

    public function updateQuantity(Request $request)
    {
        $user = auth()->guard('api')->user();
        $order = Order::where('user_id', $user->id)->where('status', 'in_cart')->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        }

        $product = $order->products()->find($request->product_id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in order',
            ], 404);
        }

        $quantity = $request->quantity <= 0 ? 1 : $request->quantity;
        $price = $product->price_after_discount > 0 ? $product->price_after_discount : $product->price;

        $order->products()->updateExistingPivot($request->product_id, [
            'quantity' => $quantity,
            'price' => $quantity * $price
        ]);

        return response()->json([
            'success' => true,
            'order' => $order
        ], 200);
    }
}