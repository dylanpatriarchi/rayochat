<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Document;
use App\Models\Conversation;
use App\Models\ChangeRequest;
use App\Jobs\ProcessDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SiteOwnerController extends Controller
{
    /**
     * Show site owner dashboard
     */
    public function dashboard()
    {
        $user = User::find(session('user_id'));
        $company = $user->company;

        if (!$company) {
            return view('site-owner.no-company');
        }

        // Dashboard stats
        $totalConversations = $company->conversations()->count();
        $conversationsToday = $company->conversations()->whereDate('created_at', today())->count();
        $averageRating = $company->conversations()->whereNotNull('rating')->avg('rating');
        $totalDocuments = $company->documents()->count();
        $pendingChangeRequests = $company->changeRequests()->where('status', 'pending')->count();

        return view('site-owner.dashboard', compact(
            'company',
            'totalConversations',
            'conversationsToday',
            'averageRating',
            'totalDocuments',
            'pendingChangeRequests'
        ));
    }

    /**
     * Show company info management
     */
    public function companyInfo()
    {
        $user = User::find(session('user_id'));
        $company = $user->company;

        return view('site-owner.company-info', compact('company'));
    }

    /**
     * Update company info
     */
    public function updateCompanyInfo(Request $request)
    {
        $user = User::find(session('user_id'));
        $company = $user->company;

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'website' => 'nullable|url',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
        ]);

        $company->update($request->only([
            'name',
            'description',
            'website',
            'email',
            'phone',
        ]));

        return back()->with('success', 'Informazioni aziendali aggiornate!');
    }

    /**
     * Show documents management
     */
    public function documents()
    {
        $user = User::find(session('user_id'));
        $company = $user->company;
        $documents = $company->documents()->latest()->paginate(20);

        return view('site-owner.documents', compact('documents', 'company'));
    }

    /**
     * Upload document
     */
    public function uploadDocument(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,md|max:5120', // 5MB max
        ]);

        $user = User::find(session('user_id'));
        $company = $user->company;

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('documents/' . $company->id, $filename);

        // Create document record
        $document = Document::create([
            'company_id' => $company->id,
            'filename' => $file->getClientOriginalName(),
            'file_path' => storage_path('app/' . $path),
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'status' => 'pending',
        ]);

        // Dispatch job to process document
        ProcessDocument::dispatch($document);

        return back()->with('success', 'Documento caricato! Sarà elaborato a breve.');
    }

    /**
     * Delete document
     */
    public function deleteDocument($id)
    {
        $user = User::find(session('user_id'));
        $company = $user->company;
        
        $document = Document::where('company_id', $company->id)
            ->findOrFail($id);

        // Delete file
        if (file_exists($document->file_path)) {
            unlink($document->file_path);
        }

        $document->delete();

        return back()->with('success', 'Documento eliminato');
    }

    /**
     * Show analytics
     */
    public function analytics()
    {
        $user = User::find(session('user_id'));
        $company = $user->company;

        // Conversations per hour (last 24 hours)
        $conversationsPerHour = $company->conversations()
            ->where('created_at', '>=', now()->subDay())
            ->select(DB::raw('EXTRACT(HOUR FROM created_at) as hour, COUNT(*) as count'))
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Average rating trend (last 7 days)
        $ratingTrend = $company->conversations()
            ->whereNotNull('rating')
            ->where('created_at', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(created_at) as date, AVG(rating) as avg_rating'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Response time stats
        $avgResponseTime = $company->conversations()->avg('response_time_ms');
        
        // Recent conversations with ratings
        $recentConversations = $company->conversations()
            ->latest()
            ->take(20)
            ->get();

        return view('site-owner.analytics', compact(
            'company',
            'conversationsPerHour',
            'ratingTrend',
            'avgResponseTime',
            'recentConversations'
        ));
    }

    /**
     * Show change requests
     */
    public function changeRequests()
    {
        $user = User::find(session('user_id'));
        $company = $user->company;
        
        $changeRequests = $company->changeRequests()
            ->with('admin')
            ->latest()
            ->paginate(20);

        return view('site-owner.change-requests', compact('changeRequests'));
    }

    /**
     * Approve change request
     */
    public function approveChangeRequest($id)
    {
        $user = User::find(session('user_id'));
        $company = $user->company;
        
        $changeRequest = ChangeRequest::where('company_id', $company->id)
            ->findOrFail($id);

        // Apply changes
        $company->update($changeRequest->proposed_changes);
        
        // Approve request
        $changeRequest->approve();

        return back()->with('success', 'Modifiche approvate e applicate');
    }

    /**
     * Reject change request
     */
    public function rejectChangeRequest(Request $request, $id)
    {
        $request->validate([
            'reason' => 'nullable|string',
        ]);

        $user = User::find(session('user_id'));
        $company = $user->company;
        
        $changeRequest = ChangeRequest::where('company_id', $company->id)
            ->findOrFail($id);

        $changeRequest->reject($request->reason);

        return back()->with('success', 'Richiesta rifiutata');
    }

    /**
     * Download WordPress plugin
     */
    public function downloadPlugin()
    {
        $pluginPath = storage_path('app/plugins/rayochat-wordpress.zip');
        
        if (!file_exists($pluginPath)) {
            return back()->with('error', 'Plugin non disponibile');
        }

        return response()->download($pluginPath, 'rayochat-wordpress.zip');
    }

    /**
     * Get API key (for display)
     */
    public function showApiKey()
    {
        $user = User::find(session('user_id'));
        $company = $user->company;

        return view('site-owner.api-key', compact('company'));
    }
}
