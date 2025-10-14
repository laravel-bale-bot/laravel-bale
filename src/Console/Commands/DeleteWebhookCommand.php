<?php

namespace Khody2012\LaravelBale\Console\Commands;

use Illuminate\Console\Command;
use Khody2012\LaravelBale\Facades\Bale;

class DeleteWebhookCommand extends Command
{
    protected $signature = 'bale:delete-webhook';
    protected $description = 'Delete the current Bale webhook.';

    public function handle(): int
    {
        $this->warn('⚠️ Deleting current Bale webhook...');

        $response = Bale::deleteWebhook();

        if (! ($response['ok'] ?? false)) {
            $this->error("❌ Failed to delete webhook: " . json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            return self::FAILURE;
        }

        $this->info('✅ Webhook deleted successfully.');
        return self::SUCCESS;
    }
}
