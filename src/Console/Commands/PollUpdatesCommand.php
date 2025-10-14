<?php

namespace LaravelBaleBot\LaravelBale\LaravelBale\Console\Commands;

use Illuminate\Console\Command;
use LaravelBaleBot\LaravelBale\LaravelBale\Facades\Bale;
use LaravelBaleBot\LaravelBale\LaravelBale\Jobs\HandleUpdateJob;

class PollUpdatesCommand extends Command
{
    protected $signature = 'bale:poll-updates {--interval=2 : Polling interval in seconds}';
    protected $description = 'Fetch updates from Bale using long polling (alternative to webhook).';

    public function handle(): int
    {
        $interval = (int) $this->option('interval');
        $this->info("🔄 Starting Bale polling every {$interval}s...");
        $offset = 0;

        while (true) {
            try {
                $updates = Bale::getUpdates(['offset' => $offset, 'timeout' => $interval + 1]);

                if (! ($updates['ok'] ?? false)) {
                    $this->error("❌ Polling failed: " . json_encode($updates, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                    sleep($interval);
                    continue;
                }

                foreach ($updates['result'] ?? [] as $update) {
                    $offset = ($update['update_id'] ?? 0) + 1;
                    HandleUpdateJob::dispatch($update);
                }

                sleep($interval);
            } catch (\Throwable $e) {
                $this->error('💥 Error: ' . $e->getMessage());
                sleep($interval);
            }
        }

        return self::SUCCESS;
    }
}
