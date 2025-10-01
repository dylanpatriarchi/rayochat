<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $roleName = $user->getRoleNames()->first() ?? 'No Role';
        
        return view('dashboard', compact('roleName'));
    }
}