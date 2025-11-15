<?php

namespace LaravelBaleBot\LaravelBale\LaravelBale\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use LaravelBaleBot\LaravelBale\LaravelBale\Events\MessageReceived;
use LaravelBaleBot\LaravelBale\LaravelBale\Listeners\StoreUserOnMessage;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the package.
     *
     * @var array
     */
    protected $listen = [
        MessageReceived::class => [
            StoreUserOnMessage::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
    }
}
