<?php

namespace LaravelBaleBot\LaravelBale\LaravelBale\Models;

use Illuminate\Database\Eloquent\Model;

class BaleUser extends Model
{
    protected $table = 'bale_users';

    protected $fillable = [
        'chat_id',
        'username',
        'first_name',
        'phone_number',
        'last_name',
        'last_message',
        'last_activity_at',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'last_activity_at' => 'datetime',
    ];
}
