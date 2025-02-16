<?php

namespace App\Http\Controllers\dashboardControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class homeController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }
}
