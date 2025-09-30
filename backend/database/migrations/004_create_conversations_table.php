<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->uuid('conversation_id')->index(); // Groups messages in same conversation
            $table->text('question');
            $table->text('answer');
            $table->string('sources')->nullable(); // Comma-separated source filenames
            $table->integer('rating')->nullable(); // 1-5 rating
            $table->timestamp('rated_at')->nullable();
            $table->integer('response_time_ms')->nullable(); // Response time in milliseconds
            $table->timestamps();
            
            $table->index(['company_id', 'created_at']);
            $table->index(['company_id', 'rating']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
