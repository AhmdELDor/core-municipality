<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use Illuminate\Console\Command;

class CleanupOldLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:cleanup {--days=90 : Number of days to keep}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old activity logs from database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');

        $this->info("Cleaning up activity logs older than {$days} days...");

        $deleted = ActivityLog::where('created_at', '<', now()->subDays($days))->delete();

        $this->info("✓ Deleted {$deleted} old log entries");

        return Command::SUCCESS;
    }
}
