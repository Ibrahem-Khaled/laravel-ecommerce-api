<?php

namespace App\Http\Controllers\api\apiV1\customerController;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use DB;
use Illuminate\Http\Request;
use App\Models\Notification;

class homeController extends Controller
{
    public function categories()
    {
        $categories = Category::where('status', 'active')->get();

        return response()->json([
            'categories' => $categories
        ]);
    }

    public function category($id)
    {
        $category = Category::find($id);

        return response()->json([
            'category' => $category
        ]);
    }

    public function getAllSubCategories()
    {
        $subCategories = SubCategory::where('status', 'active')->get();

        return response()->json([
            'subCategories' => $subCategories
        ]);
    }

    public function subCategories($id)
    {
        $subCategories = Category::find($id)->subCategories()->where('status', 'active')->get();

        return response()->json([
            'subCategories' => $subCategories
        ]);
    }


    public function getHotProducts($subCategoryId = null)
    {
        // بدء استعلام للمنتجات
        $query = Product::query();

        // في حال تم تمرير معرف الفئة الفرعية
        if ($subCategoryId) {
            // محاولة جلب الفئة الفرعية بالمعرف المحدد
            $subCategory = SubCategory::find($subCategoryId);
            if (!$subCategory) {
                // إعادة استجابة بخطأ 404 إذا لم يتم العثور على الفئة الفرعية
                return response()->json(['error' => 'الفئة الفرعية غير موجودة'], 404);
            }
            // إضافة شرط لتحديد المنتجات التي تنتمي إلى الفئة الفرعية
            $query->where('sub_category_id', $subCategory->id);
        }

        // إضافة الشروط العامة للمنتجات الساخنة والنشطة
        $products = $query->where('status', 'active')
            ->where('type', 'hot')
            ->with(['subCategory', 'user'])
            ->orderByDesc('created_at')
            ->get();
            
        // إعادة المنتجات في استجابة JSON
        return response()->json([
            'products' => $products
        ]);
    }

    public function Products($subCategory)
    {
        $products = SubCategory::find($subCategory)->products()
            ->where('status', 'active')
            ->where('type', '!=', 'hot')
            ->with(['subCategory.category', 'user'])
            ->orderByDesc('created_at')
            ->take(30)
            ->get();

        return response()->json([
            'products' => $products
        ]);
    }

    public function notification()
    {
        $user = auth()->guard('api')->user();

        // استرجاع آخر 5 اشعارات للمستخدم أو العامة
        $notifications = Notification::where('user_id', $user->id)
            ->orWhere('user_id', null)
            ->latest()
            ->take(5)
            ->get();

        // تحضير قائمة الاشعارات مع تحديد ما إذا كانت مقروءة
        $notificationsWithReadStatus = $notifications->map(function ($notification) use ($user) {
            $isRead = DB::table('user_notification_readers')
                ->where('user_id', $user->id)
                ->where('notification_id', $notification->id)
                ->exists();

            return [
                'id' => $notification->id,
                'title' => $notification->title,
                'message' => $notification->message,
                'read' => $isRead,
                'created_at' => $notification->created_at,
            ];
        });

        // حساب عدد الاشعارات الغير مقرؤة
        $unreadCount = $notificationsWithReadStatus->where('read', false)->count();

        return response()->json([
            'notifications' => $notificationsWithReadStatus,
            'unread_count' => $unreadCount,
        ]);
    }

    public function markAllNotificationsAsRead()
    {
        $user = auth()->guard('api')->user();

        // جلب جميع الاشعارات الخاصة بالمستخدم أو العامة
        $notifications = Notification::where('user_id', $user->id)
            ->orWhere('user_id', null)
            ->get();

        // تحديد كل الاشعارات كمقروءة
        foreach ($notifications as $notification) {
            // التحقق من عدم وجود سجل مسبق
            $existingRecord = DB::table('user_notification_readers')
                ->where('user_id', $user->id)
                ->where('notification_id', $notification->id)
                ->first();

            if (!$existingRecord) {
                // إضافة سجل جديد
                DB::table('user_notification_readers')->insert([
                    'user_id' => $user->id,
                    'notification_id' => $notification->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return response()->json(['message' => 'All notifications marked as read']);
    }

    public function search(Request $request)
    {
        $search = $request->query('q');
        $products = Product::where('name', 'like', '%' . $search . '%')
            ->with(['subCategory.category', 'user'])->get();
        return response()->json($products);
    }

    public function appSettings()
    {
        $appSettings = AppSettings::first();
        return response()->json($appSettings);
    }
}
