<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Analytics;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display analytics overview for all sites
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $period = $request->get('period', '30'); // Default 30 days
        $siteId = $request->get('site_id');
        
        // Calculate date range
        $endDate = Carbon::now();
        $startDate = match($period) {
            '7' => $endDate->copy()->subDays(7),
            '30' => $endDate->copy()->subDays(30),
            '90' => $endDate->copy()->subDays(90),
            '365' => $endDate->copy()->subYear(),
            default => $endDate->copy()->subDays(30)
        };

        // Base query
        $analyticsQuery = Analytics::with('site.user')
            ->whereBetween('created_at', [$startDate, $endDate]);
        
        // Filter by site if specified
        if ($siteId) {
            $analyticsQuery->where('site_id', $siteId);
        }

        // Get total messages
        $totalMessages = $analyticsQuery->count();

        // Get messages by day for chart
        $messagesByDay = Analytics::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($siteId, function($query) use ($siteId) {
                return $query->where('site_id', $siteId);
            })
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Fill missing dates with 0
        $chartData = [];
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $chartData[] = [
                'date' => $currentDate->format('d/m'),
                'count' => $messagesByDay->get($dateStr)?->count ?? 0
            ];
            $currentDate->addDay();
        }

        // Get top sites by messages
        $topSites = Analytics::select('site_id', DB::raw('COUNT(*) as message_count'))
            ->with('site.user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('site_id')
            ->orderByDesc('message_count')
            ->limit(10)
            ->get();

        // Get recent messages with details
        $recentMessages = Analytics::with('site.user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($siteId, function($query) use ($siteId) {
                return $query->where('site_id', $siteId);
            })
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        // Get all sites for filter dropdown
        $sites = Site::with('user')->orderBy('name')->get();

        // Calculate stats
        $stats = [
            'total_messages' => $totalMessages,
            'daily_average' => round($totalMessages / max(1, $startDate->diffInDays($endDate))),
            'active_sites' => Analytics::whereBetween('created_at', [$startDate, $endDate])
                ->when($siteId, function($query) use ($siteId) {
                    return $query->where('site_id', $siteId);
                })
                ->distinct('site_id')
                ->count(),
            'total_sites' => Site::count()
        ];

        return view('admin.analytics.index', compact(
            'chartData', 
            'topSites', 
            'recentMessages', 
            'sites', 
            'stats',
            'period',
            'siteId',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Show detailed analytics for a specific site
     */
    public function site(Site $site, Request $request)
    {
        $period = $request->get('period', '30');
        
        // Calculate date range
        $endDate = Carbon::now();
        $startDate = match($period) {
            '7' => $endDate->copy()->subDays(7),
            '30' => $endDate->copy()->subDays(30),
            '90' => $endDate->copy()->subDays(90),
            '365' => $endDate->copy()->subYear(),
            default => $endDate->copy()->subDays(30)
        };

        // Get messages for this site
        $messages = Analytics::where('site_id', $site->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->paginate(20);

        // Get hourly distribution
        $hourlyData = Analytics::select(
                DB::raw('EXTRACT(HOUR FROM created_at) as hour'),
                DB::raw('COUNT(*) as count')
            )
            ->where('site_id', $site->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->keyBy('hour');

        // Fill all 24 hours
        $hourlyChart = [];
        for ($i = 0; $i < 24; $i++) {
            $hourlyChart[] = [
                'hour' => sprintf('%02d:00', $i),
                'count' => $hourlyData->get($i)?->count ?? 0
            ];
        }

        // Get daily messages for chart
        $dailyData = Analytics::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('site_id', $site->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Fill missing dates
        $dailyChart = [];
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $dailyChart[] = [
                'date' => $currentDate->format('d/m'),
                'count' => $dailyData->get($dateStr)?->count ?? 0
            ];
            $currentDate->addDay();
        }

        // Calculate stats
        $totalMessages = Analytics::where('site_id', $site->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $stats = [
            'total_messages' => $totalMessages,
            'daily_average' => round($totalMessages / max(1, $startDate->diffInDays($endDate))),
            'peak_hour' => $hourlyData->sortByDesc('count')->first()?->hour ?? 0,
            'first_message' => Analytics::where('site_id', $site->id)->orderBy('created_at')->first()?->created_at,
        ];

        return view('admin.analytics.site', compact(
            'site',
            'messages',
            'hourlyChart',
            'dailyChart',
            'stats',
            'period',
            'startDate',
            'endDate'
        ));
    }
}
