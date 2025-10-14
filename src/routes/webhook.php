<?php
use Illuminate\Support\Facades\Route;
use LaravelBaleBot\LaravelBale\LaravelBale\Http\Controllers\WebhookController;

Route::prefix('bale')->group(function () {
    Route::post(config('bale.webhook.route', '/webhook'), [WebhookController::class, 'handle']);
});
