<?php

namespace App\Http\Controllers\dashboardControllers;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use Illuminate\Http\Request;

class AppSettingsController extends Controller
{
    
    public function index()
    {
        $settings = AppSettings::all();
        return view('dashboard.app-settings', compact('settings'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|string|max:255',
            'favicon' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'commission' => 'nullable|numeric',
        ]);

        AppSettings::create($request->all());

        return redirect()->route('app-settings.index')
            ->with('success', 'App Setting created successfully.');
    }
  
    public function update(Request $request, AppSettings $appSetting)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|string|max:255',
            'favicon' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'commission' => 'nullable|numeric',
        ]);

        $appSetting->update($request->all());

        return redirect()->route('app-settings.index')
            ->with('success', 'App Setting updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AppSettings $appSetting)
    {
        $appSetting->delete();

        return redirect()->route('app-settings.index')
            ->with('success', 'App Setting deleted successfully');
    }
}
