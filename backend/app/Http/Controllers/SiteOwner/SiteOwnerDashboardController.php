<?php

namespace App\Http\Controllers\SiteOwner;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\SiteInfoMD;
use Illuminate\Http\Request;

class SiteOwnerDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:site-owner');
    }

    /**
     * Display the site owner dashboard
     */
    public function index()
    {
        $user = auth()->user();
        $sites = $user->sites()->paginate(10);
        
        $stats = [
            'total_sites' => $user->sites()->count(),
            'max_sites' => $user->max_number_sites,
            'remaining_sites' => $user->max_number_sites - $user->sites()->count(),
        ];

        return view('site-owner.dashboard', compact('sites', 'stats'));
    }

    /**
     * Display a listing of sites for the authenticated site owner
     */
    public function sitesIndex()
    {
        $user = auth()->user();
        $sites = $user->sites()->with('siteInfoMD')->paginate(10);
        
        $stats = [
            'total_sites' => $user->sites()->count(),
            'max_sites' => $user->max_number_sites,
            'remaining_sites' => $user->max_number_sites - $user->sites()->count(),
        ];

        return view('site-owner.sites.index', compact('sites', 'stats'));
    }

    /**
     * Show the form for creating a new site
     */
    public function create()
    {
        $user = auth()->user();
        
        if ($user->sites()->count() >= $user->max_number_sites) {
            return redirect()->route('site-owner.sites.index')
                ->with('error', 'Hai raggiunto il limite massimo di siti (' . $user->max_number_sites . ').');
        }

        return view('site-owner.sites.create');
    }

    /**
     * Store a newly created site
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        if ($user->sites()->count() >= $user->max_number_sites) {
            return redirect()->route('site-owner.sites.index')
                ->with('error', 'Hai raggiunto il limite massimo di siti (' . $user->max_number_sites . ').');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:255',
        ]);

        $site = $user->sites()->create([
            'name' => $request->name,
            'url' => $request->url,
        ]);

        return redirect()->route('site-owner.dashboard')
            ->with('success', 'Sito creato con successo!');
    }

    /**
     * Display the specified site
     */
    public function show(Site $site)
    {
        $this->authorize('view', $site);
        $site->load('siteInfoMD');
        return view('site-owner.sites.show', compact('site'));
    }

    /**
     * Show the form for editing the specified site
     */
    public function edit(Site $site)
    {
        $this->authorize('update', $site);
        return view('site-owner.sites.edit', compact('site'));
    }

    /**
     * Update the specified site
     */
    public function update(Request $request, Site $site)
    {
        $this->authorize('update', $site);

        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:255',
        ]);

        $site->update([
            'name' => $request->name,
            'url' => $request->url,
        ]);

        return redirect()->route('site-owner.dashboard')
            ->with('success', 'Sito aggiornato con successo!');
    }

    /**
     * Remove the specified site
     */
    public function destroy(Site $site)
    {
        $this->authorize('delete', $site);
        
        $site->delete();

        return redirect()->route('site-owner.dashboard')
            ->with('success', 'Sito eliminato con successo!');
    }

    /**
     * Show the form for editing site markdown info
     */
    public function editInfo(Site $site)
    {
        $this->authorize('update', $site);
        $siteInfo = $site->siteInfoMD ?? new SiteInfoMD(['site_id' => $site->id]);
        return view('site-owner.sites.edit-info', compact('site', 'siteInfo'));
    }

    /**
     * Update site markdown info
     */
    public function updateInfo(Request $request, Site $site)
    {
        $this->authorize('update', $site);

        $request->validate([
            'markdown_content' => 'nullable|string',
        ]);

        $siteInfo = $site->siteInfoMD ?? new SiteInfoMD();
        $siteInfo->site_id = $site->id;
        $siteInfo->markdown_content = $request->markdown_content;
        $siteInfo->save();

        return redirect()->route('site-owner.sites.show', $site)
            ->with('success', 'Informazioni aziendali aggiornate con successo!');
    }
}