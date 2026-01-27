<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ComplaintSeeder::class,
            PollSeeder::class,
            SuggestionSeeder::class,
            ProjectSeeder::class,
            CircularSeeder::class,
            RequestFormSeeder::class,
            UserRequestSeeder::class,
            ExploreSeeder::class,
        ]);
    }
}
