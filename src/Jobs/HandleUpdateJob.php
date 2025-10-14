<?php

namespace LaravelBaleBot\LaravelBale\LaravelBale\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use LaravelBaleBot\LaravelBale\LaravelBale\Events\MessageReceived;
use LaravelBaleBot\LaravelBale\LaravelBale\Events\CallbackQueryReceived;

class HandleUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $update;

    public function __construct(array $update)
    {
        $this->update = $update;
    }

    public function handle()
    {
        if (isset($this->update['message'])) {
            event(new MessageReceived($this->update['message']));
        } elseif (isset($this->update['callback_query'])) {
            event(new CallbackQueryReceived($this->update['callback_query']));
        }
    }
}
