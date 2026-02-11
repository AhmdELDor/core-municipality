<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Using Eloquent to handle ULID generation automatically
        User::create([
            'full_name' => 'أحمد الدر',
            'phonenumber' => '+96176720651',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        User::where('phonenumber', '+96176720651')->delete();
    }
};
