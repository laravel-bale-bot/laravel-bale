<?php

namespace LaravelBaleBot\LaravelBale\LaravelBale\Repositories;

use Carbon\Carbon;
use LaravelBaleBot\LaravelBale\LaravelBale\Models\BaleUser;

class BaleUserRepository
{
    public function findOrCreateFromUpdate(array $update): BaleUser
    {

        $chat = $update['chat'] ?? [];
        $model = config('bale.user_model');

        return $model::updateOrCreate(
            ['chat_id' => $chat['id'] ?? null],
            [
                'username'         => $chat['username'] ?? null,
                'first_name'       => $chat['first_name'] ?? null,
                'last_name'        => $chat['last_name'] ?? null,
                'last_activity' => Carbon::now(),
            ]
        );
    }
}
