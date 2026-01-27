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
        Schema::create('explores', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('title');
            $table->text('desc');
            $table->string('category');
            $table->enum('type', ['promotion', 'post']);
            $table->foreignUlid('citizen_id')->constrained('users')->onDelete('cascade');
            $table->json('images_url')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['pending', 'approved'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('explores');
    }
};
