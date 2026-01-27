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
        Schema::create('complaints', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('title');
            $table->text('desc');
            $table->foreignUlid('user_id')->constrained('users')->cascadeOnDelete();
            $table->json('images_url')->nullable();
            $table->enum('status', ['received'])->default('received');
            $table->text(column: 'result')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
