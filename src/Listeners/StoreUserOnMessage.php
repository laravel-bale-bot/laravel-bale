<?php

namespace LaravelBaleBot\LaravelBale\LaravelBale\Listeners;

use LaravelBaleBot\LaravelBale\LaravelBale\Repositories\BaleUserRepository;
use LaravelBaleBot\LaravelBale\LaravelBale\Events\MessageReceived;

class StoreUserOnMessage
{
    public function __construct(protected BaleUserRepository $users) {}

    public function handle(MessageReceived $event)
    {
        $message = $event->message;

        $this->users->findOrCreateFromUpdate($message);
    }
}