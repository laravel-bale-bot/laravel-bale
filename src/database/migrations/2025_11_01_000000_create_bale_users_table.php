<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bale_users', function (Blueprint $table) {
            $table->id();

            $table->string('chat_id')->unique();
            $table->string('username')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone_number')->nullable();

            $table->timestamp('last_activity')->nullable();
            $table->string('state')->nullable();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });

        Schema::table('bale_users',function (Blueprint $table){
            $table->index('user_id');
            $table->index('chat_id');
            $table->index('username');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bale_users');
    }
};
