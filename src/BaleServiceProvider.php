<?php
namespace LaravelBaleBot\LaravelBale\LaravelBale;

use Illuminate\Support\ServiceProvider;
use LaravelBaleBot\LaravelBale\LaravelBale\Client\BaleHttpClient;
use LaravelBaleBot\LaravelBale\LaravelBale\Contracts\BaleClientInterface;
use LaravelBaleBot\LaravelBale\LaravelBale\Providers\EventServiceProvider;

class BaleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/bale.php', 'bale');
        $this->publishes([
            __DIR__ . '/database/migrations' => database_path('migrations'),
        ], 'bale-migrations');
        $this->app->register(EventServiceProvider::class);
        $this->app->singleton('bale', function ($app) {
            $config = $app['config']->get('bale', []);
            return new BaleHttpClient($config);
        });

        $this->app->bind(BaleClientInterface::class, function ($app) {
            return $app->make('bale');
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                \LaravelBaleBot\LaravelBale\LaravelBale\Console\Commands\SetWebhookCommand::class,
                \LaravelBaleBot\LaravelBale\LaravelBale\Console\Commands\DeleteWebhookCommand::class,
                 \LaravelBaleBot\LaravelBale\LaravelBale\Console\Commands\PollUpdatesCommand::class,
            ]);
        }
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/bale.php' => config_path('bale.php'),
        ], 'config');

        if (config('bale.webhook.enabled', true)) {
            $this->loadRoutesFrom(__DIR__.'/routes/webhook.php');
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                \LaravelBaleBot\LaravelBale\LaravelBale\Console\Commands\SetWebhookCommand::class,
                \LaravelBaleBot\LaravelBale\LaravelBale\Console\Commands\DeleteWebhookCommand::class,
                \LaravelBaleBot\LaravelBale\LaravelBale\Console\Commands\PollUpdatesCommand::class,
            ]);
        }
    }

}
