<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // This creates the table for pgvector embeddings
        // LangChain will manage this automatically, but we ensure the structure
        DB::statement('CREATE EXTENSION IF NOT EXISTS vector');
        
        // Note: LangChain's PGVector will create tables with naming pattern:
        // langchain_pg_collection and langchain_pg_embedding
        // We don't need to manually create them here
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // LangChain manages these tables
    }
};
