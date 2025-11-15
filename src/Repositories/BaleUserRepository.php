<?php

namespace LaravelBaleBot\LaravelBale\LaravelBale\Repositories;

use LaravelBaleBot\LaravelBale\LaravelBale\Models\BaleUser;

class BaleUserRepository
{
    public function findOrCreateFromUpdate(array $update): BaleUser
    {
        $chat = $update['message']['chat'] ?? [];
        $model = config('bale.user_model');

        return $model::updateOrCreate(
            ['chat_id' => $chat['id']],
            [
                'username'         => $chat['username'] ?? null,
                'first_name'       => $chat['first_name'] ?? null,
                'last_name'        => $chat['last_name'] ?? null,
                'last_message'     => $update['message']['text'] ?? null,
                'last_activity_at' => now(),
            ]
        );
    }
}
