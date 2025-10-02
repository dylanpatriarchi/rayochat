<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Redirect admin users to admin dashboard
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        
        $roleName = $user->getRoleNames()->first() ?? false;
        if($roleName) {
            return view('dashboard', compact('roleName'));
        }
        return redirect()->route('login');
    }
}