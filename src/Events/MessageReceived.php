<?php

namespace Khody2012\LaravelBale\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageReceived
{
    use Dispatchable, SerializesModels;

    public array $message;

    public function __construct(array $message)
    {
        $this->message = $message;
    }
}
