<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WidgetController extends Controller
{
    /**
     * Handle widget chat request
     */
    public function chat(Request $request)
    {
        $request->validate([
            'api_key' => 'required|string',
            'question' => 'required|string|max:1000',
            'conversation_id' => 'nullable|string',
        ]);

        // Validate API key and get company
        $company = Company::where('api_key', $request->api_key)
            ->where('is_active', true)
            ->first();

        if (!$company) {
            return response()->json([
                'error' => 'API key non valida'
            ], 401);
        }

        try {
            $startTime = microtime(true);

            // Call RAG service
            $ragUrl = config('services.rag.url') . '/ask/' . $company->hash;
            
            $response = Http::timeout(30)->post($ragUrl, [
                'question' => $request->question,
                'conversation_id' => $request->conversation_id,
            ]);

            $endTime = microtime(true);
            $responseTimeMs = round(($endTime - $startTime) * 1000);

            if ($response->successful()) {
                $data = $response->json();
                
                // Store response time
                Conversation::where('conversation_id', $data['conversation_id'])
                    ->latest()
                    ->first()
                    ?->update(['response_time_ms' => $responseTimeMs]);

                return response()->json($data);
            }

            return response()->json([
                'error' => 'Errore nel processare la richiesta'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Errore del server'
            ], 500);
        }
    }

    /**
     * Submit rating for conversation
     */
    public function rate(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        try {
            // Call RAG service to store rating
            $ragUrl = config('services.rag.url') . '/rate/' . $request->conversation_id;
            
            $response = Http::post($ragUrl, [
                'rating' => $request->rating,
            ]);

            if ($response->successful()) {
                return response()->json(['status' => 'success']);
            }

            return response()->json(['error' => 'Errore nel salvare la valutazione'], 500);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Errore del server'], 500);
        }
    }
}
