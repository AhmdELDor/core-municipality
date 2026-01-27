<?php

namespace App\Console\Commands;

use App\Models\Poll;
use Illuminate\Console\Command;

class EndExpiredPolls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'polls:end-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically end polls that have passed their end date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired polls...');

        // Find all polls that:
        // 1. Have an end_at date
        // 2. The end_at date is in the past
        // 3. Status is not already "ended"
        $expiredPolls = Poll::whereNotNull('end_at')
            ->where('end_at', '<', now())
            ->whereIn('status', ['pending', 'in_progress'])
            ->get();

        if ($expiredPolls->isEmpty()) {
            $this->info('No expired polls found.');
            return Command::SUCCESS;
        }

        $count = 0;
        foreach ($expiredPolls as $poll) {
            $poll->update(['status' => 'ended']);
            $count++;
            $this->line("✓ Ended poll: {$poll->title}");
        }

        $this->info("Successfully ended {$count} poll(s).");

        return Command::SUCCESS;
    }
}
