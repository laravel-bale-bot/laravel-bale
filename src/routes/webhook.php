<?php
use Illuminate\Support\Facades\Route;
use Khody2012\LaravelBale\Http\Controllers\WebhookController;

Route::prefix('bale')->group(function () {
    Route::post(config('bale.webhook.route', '/webhook'), [WebhookController::class, 'handle']);
});
