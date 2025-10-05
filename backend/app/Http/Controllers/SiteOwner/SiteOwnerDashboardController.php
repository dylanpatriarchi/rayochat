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
        $sites = $user->sites()->with('analytics')->get();
        
        // Basic site stats
        $totalSites = $sites->count();
        $maxSites = $user->max_number_sites;
        $remainingSites = $maxSites - $totalSites;
        
        // Analytics stats for all user's sites
        $siteIds = $sites->pluck('id');
        $totalMessages = \App\Models\Analytics::whereIn('site_id', $siteIds)->count();
        $classifiedMessages = \App\Models\Analytics::whereIn('site_id', $siteIds)->whereNotNull('category')->count();
        $classificationRate = $totalMessages > 0 ? ($classifiedMessages / $totalMessages) * 100 : 0;
        $avgConfidence = \App\Models\Analytics::whereIn('site_id', $siteIds)->whereNotNull('confidence')->avg('confidence') ?? 0;
        
        $messagesToday = \App\Models\Analytics::whereIn('site_id', $siteIds)->whereDate('created_at', today())->count();
        $messagesThisWeek = \App\Models\Analytics::whereIn('site_id', $siteIds)->where('created_at', '>=', now()->subWeek())->count();
        $messagesThisMonth = \App\Models\Analytics::whereIn('site_id', $siteIds)->where('created_at', '>=', now()->subMonth())->count();
        
        // Top category across all sites
        $topCategoryData = \App\Models\Analytics::whereIn('site_id', $siteIds)
            ->select('category', \DB::raw('COUNT(*) as count'))
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderByDesc('count')
            ->first();
        
        $stats = [
            'total_sites' => $totalSites,
            'max_sites' => $maxSites,
            'remaining_sites' => $remainingSites,
            'total_messages' => $totalMessages,
            'classified_messages' => $classifiedMessages,
            'classification_rate' => $classificationRate,
            'avg_confidence' => $avgConfidence,
            'messages_today' => $messagesToday,
            'messages_this_week' => $messagesThisWeek,
            'messages_this_month' => $messagesThisMonth,
            'top_category' => $topCategoryData ? [
                'name' => str_replace('_', ' ', $topCategoryData->category),
                'count' => $topCategoryData->count
            ] : null
        ];

        // Site performance data
        $siteStats = $sites->map(function ($site) {
            return [
                'site' => $site,
                'messages_count' => $site->analytics()->count(),
                'messages_this_week' => $site->analytics()->where('created_at', '>=', now()->subWeek())->count(),
                'messages_today' => $site->analytics()->whereDate('created_at', today())->count(),
                'top_category' => $site->analytics()
                    ->select('category', \DB::raw('COUNT(*) as count'))
                    ->whereNotNull('category')
                    ->groupBy('category')
                    ->orderByDesc('count')
                    ->first()
            ];
        })->sortByDesc('messages_count');

        // Chart data for overview (last 7 days)
        $chartData = [];
        $chartLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = \App\Models\Analytics::whereIn('site_id', $siteIds)->whereDate('created_at', $date)->count();
            $chartData[] = $count;
            $chartLabels[] = $date->format('d/m');
        }

        $weeklyChartData = [
            'labels' => $chartLabels,
            'data' => $chartData
        ];

        // Category distribution for all sites
        $categoryStats = \App\Models\Analytics::whereIn('site_id', $siteIds)
            ->select('category', \DB::raw('COUNT(*) as count'))
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->category => $item->count];
            })
            ->toArray();

        $categoryChartData = [
            'labels' => array_map(function($cat) { return str_replace('_', ' ', $cat); }, array_keys($categoryStats)),
            'data' => array_values($categoryStats)
        ];

        return view('site-owner.dashboard', compact('sites', 'stats', 'siteStats', 'weeklyChartData', 'categoryChartData'));
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

    /**
     * Show the profile page
     */
    public function profile()
    {
        $user = auth()->user();
        return view('site-owner.profile', compact('user'));
    }

    /**
     * Update the profile
     */
    public function profileUpdate(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('site-owner.profile')
            ->with('success', 'Profilo aggiornato con successo!');
    }

    /**
     * Show analytics index
     */
    public function analyticsIndex()
    {
        $user = auth()->user();
        $sites = $user->sites()->with('analytics')->get();
        
        // Calculate overall stats for this user's sites
        $totalMessages = \App\Models\Analytics::whereIn('site_id', $sites->pluck('id'))->count();
        $messagesThisMonth = \App\Models\Analytics::whereIn('site_id', $sites->pluck('id'))
            ->where('created_at', '>=', now()->subMonth())->count();
        $messagesThisWeek = \App\Models\Analytics::whereIn('site_id', $sites->pluck('id'))
            ->where('created_at', '>=', now()->subWeek())->count();
        $messagesToday = \App\Models\Analytics::whereIn('site_id', $sites->pluck('id'))
            ->whereDate('created_at', today())->count();

        $stats = [
            'total_messages' => $totalMessages,
            'messages_today' => $messagesToday,
            'messages_this_week' => $messagesThisWeek,
            'messages_this_month' => $messagesThisMonth,
            'total_sites' => $sites->count(),
        ];

        // Get site-wise message counts
        $siteStats = $sites->map(function ($site) {
            return [
                'site' => $site,
                'messages_count' => $site->analytics()->count(),
                'messages_this_week' => $site->analytics()->where('created_at', '>=', now()->subWeek())->count(),
            ];
        })->sortByDesc('messages_count');

        return view('site-owner.analytics.index', compact('stats', 'siteStats', 'sites'));
    }

    /**
     * Show analytics for specific site
     */
    public function siteAnalytics(Site $site)
    {
        $this->authorize('view', $site);
        
        // Basic stats for this site
        $totalMessages = $site->analytics()->count();
        $classifiedMessages = $site->analytics()->whereNotNull('category')->count();
        $classificationRate = $totalMessages > 0 ? ($classifiedMessages / $totalMessages) * 100 : 0;
        $avgConfidence = $site->analytics()->whereNotNull('confidence')->avg('confidence') ?? 0;
        $messagesToday = $site->analytics()->whereDate('created_at', today())->count();
        $messagesThisWeek = $site->analytics()->where('created_at', '>=', now()->subWeek())->count();
        $messagesThisMonth = $site->analytics()->where('created_at', '>=', now()->subMonth())->count();

        // Top category for this site
        $topCategoryData = $site->analytics()
            ->select('category', \DB::raw('COUNT(*) as count'))
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderByDesc('count')
            ->first();

        $stats = [
            'total_messages' => $totalMessages,
            'classified_messages' => $classifiedMessages,
            'classification_rate' => $classificationRate,
            'avg_confidence' => $avgConfidence,
            'messages_today' => $messagesToday,
            'messages_this_week' => $messagesThisWeek,
            'messages_this_month' => $messagesThisMonth,
            'top_category' => $topCategoryData ? [
                'name' => str_replace('_', ' ', $topCategoryData->category),
                'count' => $topCategoryData->count
            ] : null
        ];

        // Category statistics for this site
        $categoryStats = $site->analytics()
            ->select('category', \DB::raw('COUNT(*) as count'), \DB::raw('AVG(confidence) as avg_confidence'))
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderByDesc('count')
            ->get()
            ->mapWithKeys(function ($item) use ($totalMessages) {
                return [$item->category => [
                    'count' => $item->count,
                    'percentage' => $totalMessages > 0 ? round(($item->count / $totalMessages) * 100, 2) : 0,
                    'avg_confidence' => $item->avg_confidence ?? 0
                ]];
            })
            ->toArray();

        // Chart data for category distribution
        $categoryChartData = [
            'labels' => array_map(function($cat) { return str_replace('_', ' ', $cat); }, array_keys($categoryStats)),
            'data' => array_values(array_column($categoryStats, 'count'))
        ];

        // Timeline data (last 30 days)
        $timelineData = [];
        $timelineLabels = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = $site->analytics()->whereDate('created_at', $date)->count();
            $timelineData[] = $count;
            $timelineLabels[] = $date->format('d/m');
        }

        $timelineChartData = [
            'labels' => $timelineLabels,
            'data' => $timelineData
        ];

        // Recent classified messages for this site
        $recentMessages = $site->analytics()
            ->whereNotNull('category')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Hourly distribution (last 7 days)
        $hourlyData = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $count = $site->analytics()
                ->where('created_at', '>=', now()->subDays(7))
                ->whereRaw('EXTRACT(hour FROM created_at) = ?', [$hour])
                ->count();
            $hourlyData[] = $count;
        }

        $hourlyChartData = [
            'labels' => array_map(function($h) { return sprintf('%02d:00', $h); }, range(0, 23)),
            'data' => $hourlyData
        ];

        // Most common keywords for this site
        $allMessages = $site->analytics()
            ->whereNotNull('classification_data')
            ->get();

        $keywordCounts = [];
        foreach ($allMessages as $message) {
            if (isset($message->classification_data['keywords'])) {
                foreach ($message->classification_data['keywords'] as $keyword) {
                    $keywordCounts[$keyword] = ($keywordCounts[$keyword] ?? 0) + 1;
                }
            }
        }
        
        arsort($keywordCounts);
        $topKeywords = array_slice($keywordCounts, 0, 10, true);

        return view('site-owner.analytics.site', compact(
            'site',
            'stats',
            'categoryStats',
            'categoryChartData',
            'timelineChartData',
            'hourlyChartData',
            'recentMessages',
            'topKeywords'
        ));
    }
}