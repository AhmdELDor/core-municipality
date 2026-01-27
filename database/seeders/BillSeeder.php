<?php

namespace Database\Seeders;

use App\Models\Bill;
use Illuminate\Database\Seeder;

class BillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bills = [
            [
                'title' => 'Water Bill',
                'description' => 'Monthly water consumption charges',
                'amount' => 150.00,
                'payment_type' => 'monthly',
            ],
            [
                'title' => 'Electricity Bill',
                'description' => 'Monthly electricity consumption charges',
                'amount' => 250.00,
                'payment_type' => 'monthly',
            ],
            [
                'title' => 'Property Tax',
                'description' => 'Annual property tax assessment',
                'amount' => 1200.00,
                'payment_type' => 'annual',
            ],
            [
                'title' => 'Waste Collection Fee',
                'description' => 'Monthly waste collection and disposal service',
                'amount' => 50.00,
                'payment_type' => 'monthly',
            ],
            [
                'title' => 'Sewage Service',
                'description' => 'Monthly sewage and drainage service charges',
                'amount' => 75.00,
                'payment_type' => 'monthly',
            ],
            [
                'title' => 'Street Lighting Fee',
                'description' => 'Quarterly street lighting maintenance fee',
                'amount' => 30.00,
                'payment_type' => 'quarterly',
            ],
            [
                'title' => 'Building Permit Fee',
                'description' => 'One-time fee for building permit application',
                'amount' => 500.00,
                'payment_type' => 'one-time',
            ],
            [
                'title' => 'Business License',
                'description' => 'Annual business operation license fee',
                'amount' => 800.00,
                'payment_type' => 'annual',
            ],
        ];

        foreach ($bills as $bill) {
            Bill::create($bill);
        }

        $this->command->info('Bills seeded successfully!');
    }
}
