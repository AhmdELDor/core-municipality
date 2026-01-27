<?php

namespace Database\Seeders;

use App\Models\DeviceToken;
use App\Models\User;
use Illuminate\Database\Seeder;

class DeviceTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users to create device tokens for
        $users = User::whereIn('role', ['citizen', 'admin'])->take(15)->get();

        $deviceTypes = ['android', 'ios'];
        $androidDevices = ['Samsung Galaxy S21', 'Google Pixel 6', 'OnePlus 9', 'Xiaomi Mi 11', 'Huawei P40'];
        $iosDevices = ['iPhone 13 Pro', 'iPhone 12', 'iPhone 14', 'iPhone 11', 'iPad Pro'];

        foreach ($users as $user) {
            // Each user can have 1-2 devices
            $deviceCount = rand(1, 2);

            for ($i = 0; $i < $deviceCount; $i++) {
                $deviceType = $deviceTypes[array_rand($deviceTypes)];
                $deviceName = $deviceType === 'android'
                    ? $androidDevices[array_rand($androidDevices)]
                    : $iosDevices[array_rand($iosDevices)];

                DeviceToken::create([
                    'user_id' => $user->id,
                    'device_token' => 'fake_fcm_token_' . $user->id . '_' . uniqid() . '_' . time(),
                    'device_type' => $deviceType,
                    'device_name' => $deviceName,
                    'is_active' => rand(0, 10) > 1, // 90% active
                    'last_used_at' => now()->subDays(rand(0, 30)),
                ]);
            }
        }
    }
}
