<?php

namespace LaravelBaleBot\LaravelBale\LaravelBale\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use LaravelBaleBot\LaravelBale\LaravelBale\Jobs\HandleUpdateJob;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $update = $request->all();

        if (config('bale.use_queue', true)) {
            HandleUpdateJob::dispatch($update);
        } else {
            (new HandleUpdateJob($update))->handle();
        }

        return response()->json(['ok' => true]);
    }
}
