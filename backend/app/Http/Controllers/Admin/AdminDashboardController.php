<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{

    /**
     * Display the admin dashboard
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'site_owners' => User::role('site-owner')->count(),
            'admins' => User::role('admin')->count(),
            'recent_logins' => User::whereNotNull('last_login_at')
                ->where('last_login_at', '>=', now()->subDays(7))
                ->count(),
        ];

        $recentSiteOwners = User::role('site-owner')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentSiteOwners'));
    }

    /**
     * Display a listing of site owners
     */
    public function siteOwnersIndex()
    {
        $siteOwners = User::role('site-owner')->with('roles')->paginate(10);
        return view('admin.site-owners.index', compact('siteOwners'));
    }

    /**
     * Show the form for creating a new site owner
     */
    public function siteOwnersCreate()
    {
        return view('admin.site-owners.create');
    }

    /**
     * Store a newly created site owner
     */
    public function siteOwnersStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'max_number_sites' => 'required|integer|min:1|max:10',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'max_number_sites' => $request->max_number_sites,
        ]);

        $user->assignRole('site-owner');

        return redirect()->route('admin.site-owners.index')
            ->with('success', 'Site Owner creato con successo!');
    }

    /**
     * Display the specified site owner
     */
    public function siteOwnersShow(User $siteOwner)
    {
        return view('admin.site-owners.show', compact('siteOwner'));
    }

    /**
     * Show the form for editing the specified site owner
     */
    public function siteOwnersEdit(User $siteOwner)
    {
        return view('admin.site-owners.edit', compact('siteOwner'));
    }

    /**
     * Update the specified site owner
     */
    public function siteOwnersUpdate(Request $request, User $siteOwner)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $siteOwner->id,
            'max_number_sites' => 'required|integer|min:1|max:10',
        ]);

        $siteOwner->update([
            'name' => $request->name,
            'email' => $request->email,
            'max_number_sites' => $request->max_number_sites,
        ]);

        return redirect()->route('admin.site-owners.index')
            ->with('success', 'Site Owner aggiornato con successo!');
    }

    /**
     * Remove the specified site owner
     */
    public function siteOwnersDestroy(User $siteOwner)
    {
        $siteOwner->delete();

        return redirect()->route('admin.site-owners.index')
            ->with('success', 'Site Owner eliminato con successo!');
    }
}
