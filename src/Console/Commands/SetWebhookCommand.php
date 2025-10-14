<?php

namespace Khody2012\LaravelBale\Console\Commands;

use Illuminate\Console\Command;
use Khody2012\LaravelBale\Facades\Bale;

class SetWebhookCommand extends Command
{
    protected $signature = 'bale:set-webhook 
        {url? : Optional. If not provided, uses config("bale.webhook.url")}';

    protected $description = 'Set Bale webhook URL for receiving updates.';

    public function handle(): int
    {
        $url = $this->argument('url') ?? config('bale.webhook.url');

        if (! $url) {
            $this->error('❌ Webhook URL not provided. Please set BALE_WEBHOOK_URL in your .env or pass it as argument.');
            return self::FAILURE;
        }

        $this->info("🔗 Setting webhook to: {$url}");

        $response = Bale::setWebhook($url);

        if (! ($response['ok'] ?? false)) {
            $this->error("❌ Failed to set webhook: " . json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            return self::FAILURE;
        }

        $this->info("✅ Webhook successfully set!");
        return self::SUCCESS;
    }
}
