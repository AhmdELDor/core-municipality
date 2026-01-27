<?php

namespace Database\Seeders;

use App\Models\AttachBill;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AttachBillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all citizens (users with role 'user')
        $citizens = User::where('role', 'citizen')->get();

        if ($citizens->isEmpty()) {
            $this->command->warn('No citizens found. Please seed users first.');
            return;
        }

        $attachedBills = [
            [
                'title' => 'Water Bill - January 2026',
                'desc' => 'Water consumption for January 2026',
                'amount' => 150.00,
                'due_date' => Carbon::parse('2026-01-31'),
                'paid_date' => null,
                'note' => 'Payment due by end of month',
            ],
            [
                'title' => 'Electricity Bill - January 2026',
                'desc' => 'Electricity consumption for January 2026',
                'amount' => 250.00,
                'due_date' => Carbon::parse('2026-01-31'),
                'paid_date' => Carbon::parse('2026-01-15'),
                'note' => 'Paid on time',
            ],
            [
                'title' => 'Property Tax - 2026',
                'desc' => 'Annual property tax for residential property',
                'amount' => 1200.00,
                'due_date' => Carbon::parse('2026-12-31'),
                'paid_date' => null,
                'note' => 'Annual payment due by December 31',
            ],
            [
                'title' => 'Waste Collection - January 2026',
                'desc' => 'Monthly waste collection service fee',
                'amount' => 50.00,
                'due_date' => Carbon::parse('2026-01-20'),
                'paid_date' => Carbon::parse('2026-01-10'),
                'note' => 'Paid early',
            ],
            [
                'title' => 'Sewage Service - January 2026',
                'desc' => 'Monthly sewage and drainage service',
                'amount' => 75.00,
                'due_date' => Carbon::parse('2026-01-31'),
                'paid_date' => null,
                'note' => 'Pending payment',
            ],
        ];

        // Attach bills to random citizens
        foreach ($citizens->take(3) as $citizen) {
            // Each citizen gets 2-4 random bills
            $numberOfBills = rand(2, 4);
            $selectedBills = collect($attachedBills)->random($numberOfBills);

            foreach ($selectedBills as $bill) {
                AttachBill::create([
                    'citizen_id' => $citizen->id,
                    'title' => $bill['title'],
                    'desc' => $bill['desc'],
                    'amount' => $bill['amount'],
                    'due_date' => $bill['due_date'],
                    'paid_date' => $bill['paid_date'],
                    'note' => $bill['note'],
                ]);
            }
        }

        $this->command->info('Attached bills seeded successfully!');
    }
}
