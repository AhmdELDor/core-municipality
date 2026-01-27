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
        Schema::create('attach_bills', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('citizen_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('desc')->nullable();
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->date('paid_date')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attach_bills');
    }
};
