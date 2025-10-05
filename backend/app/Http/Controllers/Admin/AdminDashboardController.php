<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Site;
use App\Models\SiteInfoMD;
use App\Models\Analytics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Display a listing of all users with their sites (new unified interface)
     * Excludes admin users - they manage themselves via profile
     */
    public function usersIndex()
    {
        $users = User::with(['sites.siteInfoMD', 'roles'])
            ->whereDoesntHave('roles', function($query) {
                $query->where('name', 'admin');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function usersCreate()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user
     */
    public function usersStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'max_number_sites' => 'required|integer|min:1|max:999',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'max_number_sites' => $request->max_number_sites,
        ]);

        // Always assign site-owner role (no admin creation from UI)
        $user->assignRole('site-owner');

        // Send welcome email
        try {
            \Mail::to($user->email)->send(new \App\Mail\WelcomeUserMail($user));
            $message = 'Utente creato con successo! Email di benvenuto inviata.';
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email: ' . $e->getMessage());
            $message = 'Utente creato con successo! (Errore nell\'invio email)';
        }

        return redirect()->route('admin.users.index')
            ->with('success', $message);
    }

    /**
     * Show the form for editing the specified user
     */
    public function usersEdit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function usersUpdate(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'max_number_sites' => 'required|integer|min:1|max:999',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'max_number_sites' => $request->max_number_sites,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utente aggiornato con successo!');
    }

    /**
     * Remove the specified user (only if not admin)
     */
    public function usersDestroy(User $user)
    {
        // Prevent deletion of admin users
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Non è possibile eliminare un utente amministratore.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Utente {$userName} eliminato con successo!");
    }

    /**
     * Display the admin dashboard with overview
     */
    public function index()
    {
        // Get basic stats
        $stats = [
            'total_users' => User::count(),
            'site_owners' => User::role('site-owner')->count(),
            'admins' => User::role('admin')->count(),
            'total_sites' => Site::count(),
            'active_sites' => Site::whereHas('analytics', function($query) {
                $query->where('created_at', '>=', now()->subDays(30));
            })->count(),
            'total_messages' => Analytics::count(),
            'messages_today' => Analytics::whereDate('created_at', today())->count(),
            'messages_this_week' => Analytics::where('created_at', '>=', now()->subWeek())->count(),
            'messages_this_month' => Analytics::where('created_at', '>=', now()->subMonth())->count(),
        ];

        // Get recent activity
        $recentUsers = User::role('site-owner')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentSites = Site::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentMessages = Analytics::with('site.user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get top sites by messages (last 30 days)
        $topSites = Analytics::select('site_id', DB::raw('COUNT(*) as message_count'))
            ->with('site.user')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('site_id')
            ->orderByDesc('message_count')
            ->limit(5)
            ->get();

        // Get messages chart data (last 7 days)
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = Analytics::whereDate('created_at', $date)->count();
            $chartData[] = [
                'date' => $date->format('d/m'),
                'count' => $count
            ];
        }

        // System health checks
        $systemHealth = [
            'database' => 'healthy',
            'storage' => disk_free_space(storage_path()) > 1000000000 ? 'healthy' : 'warning', // 1GB
            'cache' => 'healthy',
        ];

        return view('admin.dashboard', compact(
            'stats', 
            'recentUsers', 
            'recentSites', 
            'recentMessages', 
            'topSites', 
            'chartData',
            'systemHealth'
        ));
    }

    /**
     * Display admin profile
     */
    public function profile()
    {
        return view('admin.profile');
    }

    /**
     * Update admin profile
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

        return redirect()->route('admin.profile')
            ->with('success', 'Profilo aggiornato con successo!');
    }

    /**
     * Display a listing of site owners (redirect to new unified interface)
     */
    public function siteOwnersIndex()
    {
        return redirect()->route('admin.users.index')
            ->with('info', 'Gestione utenti spostata nella nuova interfaccia unificata.');
    }

    /**
     * Show the form for creating a new site owner (redirect to new interface)
     */
    public function siteOwnersCreate()
    {
        return redirect()->route('admin.users.create')
            ->with('info', 'Creazione utenti spostata nella nuova interfaccia.');
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
     * Display the specified site owner (redirect to new interface)
     */
    public function siteOwnersShow(User $siteOwner)
    {
        return redirect()->route('admin.users.index')
            ->with('info', 'Visualizzazione utenti disponibile nella nuova interfaccia con accordion.');
    }

    /**
     * Show the form for editing the specified site owner (redirect to new interface)
     */
    public function siteOwnersEdit(User $siteOwner)
    {
        return redirect()->route('admin.users.edit', $siteOwner)
            ->with('info', 'Modifica utente spostata nella nuova interfaccia.');
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

    /**
     * Display a listing of all sites (redirect to new unified interface)
     */
    public function sitesIndex()
    {
        return redirect()->route('admin.users.index')
            ->with('info', 'Gestione siti spostata nella nuova interfaccia utenti con accordion.');
    }

    /**
     * Display the specified site
     */
    public function sitesShow(Site $site)
    {
        $site->load('siteInfoMD');
        return view('admin.sites.show', compact('site'));
    }

    /**
     * Show the form for editing the specified site
     */
    public function sitesEdit(Site $site)
    {
        return view('admin.sites.edit', compact('site'));
    }

    /**
     * Update the specified site
     */
    public function sitesUpdate(Request $request, Site $site)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:255',
        ]);

        $site->update([
            'name' => $request->name,
            'url' => $request->url,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Sito aggiornato con successo!');
    }

    /**
     * Remove the specified site
     */
    public function sitesDestroy(Site $site)
    {
        $siteOwnerName = $site->user->name;
        $site->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Sito di {$siteOwnerName} eliminato con successo!");
    }

    /**
     * Show the form for editing site markdown info
     */
    public function sitesEditInfo(Site $site)
    {
        $siteInfo = $site->siteInfoMD ?? new SiteInfoMD(['site_id' => $site->id]);
        return view('admin.sites.edit-info', compact('site', 'siteInfo'));
    }

    /**
     * Update site markdown info
     */
    public function sitesUpdateInfo(Request $request, Site $site)
    {
        $request->validate([
            'markdown_content' => 'nullable|string',
        ]);

        $siteInfo = $site->siteInfoMD ?? new SiteInfoMD();
        $siteInfo->site_id = $site->id;
        $siteInfo->markdown_content = $request->markdown_content;
        $siteInfo->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Informazioni aziendali aggiornate con successo!');
    }

    /**
     * Display analytics page with ML classification data
     */
    public function analyticsIndex()
    {
        // Basic stats
        $totalMessages = Analytics::count();
        $classifiedMessages = Analytics::whereNotNull('category')->count();
        $classificationRate = $totalMessages > 0 ? ($classifiedMessages / $totalMessages) * 100 : 0;
        $avgConfidence = Analytics::whereNotNull('confidence')->avg('confidence') ?? 0;
        $messagesToday = Analytics::whereDate('created_at', today())->count();

        // Top category
        $topCategoryData = Analytics::select('category', DB::raw('COUNT(*) as count'))
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
            'top_category' => $topCategoryData ? [
                'name' => str_replace('_', ' ', $topCategoryData->category),
                'count' => $topCategoryData->count
            ] : null
        ];

        // Category statistics
        $categoryStats = Analytics::select('category', DB::raw('COUNT(*) as count'), DB::raw('AVG(confidence) as avg_confidence'))
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
            $count = Analytics::whereDate('created_at', $date)->count();
            $timelineData[] = $count;
            $timelineLabels[] = $date->format('d/m');
        }

        $timelineChartData = [
            'labels' => $timelineLabels,
            'data' => $timelineData
        ];

        // Recent classified messages
        $recentMessages = Analytics::with(['site.user'])
            ->whereNotNull('category')
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();

        return view('admin.analytics.index', compact(
            'stats',
            'categoryStats',
            'categoryChartData',
            'timelineChartData',
            'recentMessages'
        ));
    }

    /**
     * Display analytics for a specific site
     */
    public function siteAnalytics(Site $site)
    {
        // Basic stats for this site
        $totalMessages = Analytics::where('site_id', $site->id)->count();
        $classifiedMessages = Analytics::where('site_id', $site->id)->whereNotNull('category')->count();
        $classificationRate = $totalMessages > 0 ? ($classifiedMessages / $totalMessages) * 100 : 0;
        $avgConfidence = Analytics::where('site_id', $site->id)->whereNotNull('confidence')->avg('confidence') ?? 0;
        $messagesToday = Analytics::where('site_id', $site->id)->whereDate('created_at', today())->count();
        $messagesThisWeek = Analytics::where('site_id', $site->id)->where('created_at', '>=', now()->subWeek())->count();
        $messagesThisMonth = Analytics::where('site_id', $site->id)->where('created_at', '>=', now()->subMonth())->count();

        // Top category for this site
        $topCategoryData = Analytics::select('category', DB::raw('COUNT(*) as count'))
            ->where('site_id', $site->id)
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
        $categoryStats = Analytics::select('category', DB::raw('COUNT(*) as count'), DB::raw('AVG(confidence) as avg_confidence'))
            ->where('site_id', $site->id)
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

        // Timeline data (last 30 days) for this site
        $timelineData = [];
        $timelineLabels = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = Analytics::where('site_id', $site->id)->whereDate('created_at', $date)->count();
            $timelineData[] = $count;
            $timelineLabels[] = $date->format('d/m');
        }

        $timelineChartData = [
            'labels' => $timelineLabels,
            'data' => $timelineData
        ];

        // Recent classified messages for this site
        $recentMessages = Analytics::where('site_id', $site->id)
            ->whereNotNull('category')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Hourly distribution (last 7 days)
        $hourlyData = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $count = Analytics::where('site_id', $site->id)
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
        $allMessages = Analytics::where('site_id', $site->id)
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

        return view('admin.analytics.site', compact(
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
