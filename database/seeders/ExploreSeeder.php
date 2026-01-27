<?php

namespace Database\Seeders;

use App\Models\Explore;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExploreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have users to attach explores to
        if (User::count() === 0) {
            User::factory(10)->create();
        }

        $users = User::all();

        foreach ($users as $user) {
            // Create a mix of 'promotion' and 'post' types
            for ($i = 0; $i < rand(1, 3); $i++) {
                Explore::create([
                    'title' => fake()->sentence(4),
                    'desc' => fake()->paragraph(),
                    'category' => fake()->randomElement(['Events', 'News', 'Announcements', 'Tourism']),
                    'type' => fake()->randomElement(['promotion', 'post']),
                    'citizen_id' => $user->id,
                    'images_url' => [fake()->imageUrl(640, 480, 'city')],
                    'start_date' => fake()->boolean(60) ? fake()->dateTimeBetween('-1 month', 'now') : null,
                    'end_date' => fake()->boolean(60) ? fake()->dateTimeBetween('now', '+1 month') : null,
                    'status' => fake()->randomElement(['pending', 'approved']),
                ]);
            }
        }
    }
}
