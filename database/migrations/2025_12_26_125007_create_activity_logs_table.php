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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_name')->nullable(); // e.g., 'user', 'admin', 'payment'
            $table->text('description');
            $table->string('subject_type')->nullable(); // Model class
            $table->ulid('subject_id')->nullable(); // Model ID
            $table->string('event')->nullable(); // created, updated, deleted, login, logout
            $table->string('causer_type')->nullable(); // User model
            $table->ulid('causer_id')->nullable(); // User ID
            $table->json('properties')->nullable(); // Additional data
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('created_at')->nullable();

            // Indexes for performance
            $table->index(['subject_type', 'subject_id']);
            $table->index(['causer_type', 'causer_id']);
            $table->index('log_name');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
