<?php

namespace App\Http\Controllers\api\apiV1\customerController;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Notification;

class homeController extends Controller
{
    public function categories()
    {
        $categories = Category::where('status', 'active')
            ->whereHas('products', function ($q) {
                $q->where('status', 'active')
                    ->where('type', 'basic');
            })
            ->with([
                'products' => function ($q) {
                    $q->where('status', 'active')
                        ->where('type', 'basic')
                        ->take(5);
                }
            ])
            ->get();

        return response()->json([
            'categories' => $categories
        ]);
    }

    public function category($id)
    {
        $category = Category::with([
            'products' => function ($q) {
                $q->where('status', 'active')
                    ->where('type', 'basic');
            }
        ])->find($id);

        return response()->json([
            'category' => $category
        ]);
    }

    public function hotProducts()
    {
        $products = Product::where('status', 'active')->where('type', 'hot')->with([
            'category',
            'user'
        ])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'products' => $products
        ]);
    }

    public function notification()
    {
        $user = auth()->guard('api')->user();

        $notifications = Notification::where('user_id', $user->id)->orWhere('user_id', null)->latest()->take(5)->get();
        return response()->json($notifications);
    }

}
