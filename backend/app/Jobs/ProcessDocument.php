<?php

namespace App\Jobs;

use App\Models\Document;
use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessDocument implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 300;

    protected $document;

    /**
     * Create a new job instance.
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Update status to processing
            $this->document->update(['status' => 'processing']);

            $company = $this->document->company;
            
            // Call RAG service to index the document
            $ragUrl = config('services.rag.url') . '/index/' . $company->hash;
            
            $response = Http::timeout(300)->post($ragUrl);

            if ($response->successful()) {
                $this->document->update([
                    'status' => 'processed',
                    'processed_at' => now(),
                ]);
                
                Log::info("Document processed successfully", [
                    'document_id' => $this->document->id,
                    'filename' => $this->document->filename,
                ]);
            } else {
                throw new \Exception("RAG service returned error: " . $response->body());
            }

        } catch (\Exception $e) {
            Log::error("Document processing failed", [
                'document_id' => $this->document->id,
                'error' => $e->getMessage(),
            ]);

            $this->document->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
