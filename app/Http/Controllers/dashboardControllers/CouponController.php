<?php

namespace App\Http\Controllers\dashboardControllers;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::all();
        return view('dashboard.coupons.index', compact('coupons'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|integer',
        ]);

        $coupons = [];
        for ($i = 0; $i < $request->quantity; $i++) {
            $coupons[] = [
                'code' => random_int(100000, 999999),
                'type' => $request->type,
                'value' => $request->value,
            ];
        }

        Coupon::insert($coupons);

        return redirect()->route('coupons.index')->with('success', 'Coupons created successfully.');
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|integer',
            'is_used' => 'boolean',
        ]);

        $coupon->update($request->all());

        return redirect()->route('coupons.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('coupons.index')->with('success', 'Coupon deleted successfully.');
    }
}
