<?php

namespace App\Http\Controllers\dashboardControllers;

use App\Http\Controllers\Controller;
use App\Models\SelectStore;
use Illuminate\Http\Request;

class SelectStoreController extends Controller
{
    public function index()
    {
        // جلب جميع السجلات من الجدول
        $stores = SelectStore::all();
        // عرضها في الصفحة stores/index.blade.php
        return view('dashboard.stores', compact('stores'));
    }
    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|string',
            'icon' => 'nullable|string',
            'navigation' => 'required|string',
        ]);

        // إنشاء السجل وحفظه
        SelectStore::create($request->all());

        // إعادة التوجيه لقائمة السجلات مع رسالة نجاح
        return redirect()->route('select-stores.index')
            ->with('success', 'تمت إضافة المتجر بنجاح!');
    }

    public function update(Request $request, $id)
    {
        // التحقق من صحة البيانات
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:active,inactive',
            'image' => 'nullable|string',
            'icon' => 'nullable|string',
            'navigation' => 'sometimes|string',
        ]);

        $store = SelectStore::findOrFail($id);
        $store->update($request->all());

        return redirect()->route('select-stores.index')
            ->with('success', 'تم تعديل بيانات المتجر بنجاح!');
    }

    /**
     * حذف سجل محدد.
     */
    public function destroy($id)
    {
        $store = SelectStore::findOrFail($id);
        $store->delete();

        return redirect()->route('select-stores.index')
            ->with('success', 'تم حذف المتجر بنجاح!');
    }
}
