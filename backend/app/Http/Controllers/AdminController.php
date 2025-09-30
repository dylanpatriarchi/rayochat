<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Conversation;
use App\Models\ChangeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        $totalSiteOwners = User::where('role', 'site_owner')->count();
        $activeCompanies = Company::where('is_active', true)->count();
        $totalConversations = Conversation::count();
        
        // Recent conversations across all companies
        $recentConversations = Conversation::with('company')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalSiteOwners',
            'activeCompanies',
            'totalConversations',
            'recentConversations'
        ));
    }

    /**
     * List all site owners
     */
    public function siteOwners()
    {
        $siteOwners = User::where('role', 'site_owner')
            ->with('company')
            ->latest()
            ->paginate(20);

        return view('admin.site-owners', compact('siteOwners'));
    }

    /**
     * Show create site owner form
     */
    public function createSiteOwner()
    {
        return view('admin.create-site-owner');
    }

    /**
     * Store new site owner
     */
    public function storeSiteOwner(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'company_name' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => 'site_owner',
                'email_verified_at' => now(),
            ]);

            // Create company
            Company::create([
                'user_id' => $user->id,
                'name' => $request->company_name,
            ]);
        });

        return redirect()->route('admin.site-owners')
            ->with('success', 'Site Owner creato con successo!');
    }

    /**
     * Show site owner analytics
     */
    public function siteOwnerAnalytics($id)
    {
        $siteOwner = User::with('company')->findOrFail($id);
        $company = $siteOwner->company;

        if (!$company) {
            return back()->with('error', 'Azienda non trovata');
        }

        // Analytics data
        $totalConversations = $company->conversations()->count();
        $averageRating = $company->conversations()->whereNotNull('rating')->avg('rating');
        $conversationsToday = $company->conversations()->whereDate('created_at', today())->count();
        
        // Conversations per hour (last 24 hours)
        $conversationsPerHour = $company->conversations()
            ->where('created_at', '>=', now()->subDay())
            ->select(DB::raw('EXTRACT(HOUR FROM created_at) as hour, COUNT(*) as count'))
            ->groupBy('hour')
            ->get();

        return view('admin.site-owner-analytics', compact(
            'siteOwner',
            'company',
            'totalConversations',
            'averageRating',
            'conversationsToday',
            'conversationsPerHour'
        ));
    }

    /**
     * Deactivate site owner
     */
    public function deactivateSiteOwner($id)
    {
        $siteOwner = User::findOrFail($id);
        $siteOwner->update(['is_active' => false]);
        
        if ($siteOwner->company) {
            $siteOwner->company->update(['is_active' => false]);
        }

        return back()->with('success', 'Site Owner disattivato');
    }

    /**
     * Activate site owner
     */
    public function activateSiteOwner($id)
    {
        $siteOwner = User::findOrFail($id);
        $siteOwner->update(['is_active' => true]);
        
        if ($siteOwner->company) {
            $siteOwner->company->update(['is_active' => true]);
        }

        return back()->with('success', 'Site Owner attivato');
    }

    /**
     * Show change request form
     */
    public function createChangeRequest($companyId)
    {
        $company = Company::findOrFail($companyId);
        return view('admin.create-change-request', compact('company'));
    }

    /**
     * Store change request
     */
    public function storeChangeRequest(Request $request, $companyId)
    {
        $request->validate([
            'description' => 'required|string',
            'proposed_changes' => 'required|array',
        ]);

        ChangeRequest::create([
            'company_id' => $companyId,
            'requested_by_admin_id' => session('user_id'),
            'description' => $request->description,
            'proposed_changes' => $request->proposed_changes,
        ]);

        return redirect()->route('admin.site-owner-analytics', $companyId)
            ->with('success', 'Richiesta di modifica inviata');
    }
}
