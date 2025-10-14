<?php

namespace Khody2012\LaravelBale\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallbackQueryReceived
{
    use Dispatchable, SerializesModels;

    public array $callbackQuery;

    public function __construct(array $callbackQuery)
    {
        $this->callbackQuery = $callbackQuery;
    }
}
