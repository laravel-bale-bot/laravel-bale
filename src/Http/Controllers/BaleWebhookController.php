<?php

namespace LaravelBaleBot\LaravelBale\LaravelBale\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use LaravelBaleBot\LaravelBale\LaravelBale\Jobs\HandleUpdateJob;

class BaleWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $update = $request->all();
        return $this->handleUpdate($update);
    }

    /**
     * Override this in your custom controller
     */
    protected function handleUpdate(array $update)
    {
        // Default behavior: dispatch to queue
        HandleUpdateJob::dispatch($update);
        return response()->json(['ok' => true]);
    }
}