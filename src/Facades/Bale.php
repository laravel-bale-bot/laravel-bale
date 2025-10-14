<?php

namespace LaravelBaleBot\LaravelBale\LaravelBale\Facades;

use Illuminate\Support\Facades\Facade;

class Bale extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'bale';
    }
}
