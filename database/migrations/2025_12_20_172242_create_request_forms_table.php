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
        Schema::create('request_forms', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('fields'); // The dynamic inputs: [{"name":"age", "type":"number"}]
            $table->string('version')->default('1.0');
            $table->string('status')->default('active'); // active, inactive
            $table->text('instructions')->nullable();
            $table->json('attachments_required')->nullable(); // ["ID Card", "Deed"]
            $table->decimal('fee_amount', 10, 2)->default(0);
            $table->json('allowed_file_types')->nullable(); // ["pdf", "png"]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_forms');
    }
};
