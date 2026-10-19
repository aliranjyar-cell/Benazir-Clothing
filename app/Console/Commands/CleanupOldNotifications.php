<?php

namespace App\Console\Commands;

use App\Models\Notification;
use Illuminate\Console\Command;

class CleanupOldNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:cleanup {--days=7 : Number of days after which notifications are deleted}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete notifications older than the given number of days.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int) $this->option('days');

        $deleted = Notification::query()
            ->where('created_at', '<', now()->subDays($days))
            ->delete();

        $this->info("Deleted {$deleted} notification(s) older than {$days} day(s).");

        return self::SUCCESS;
    }
}
