<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::dropIfExists('guardians');

Schema::create('guardians', function (Blueprint $table) {
    $table->id();
    
    // Check type of users.id
    $col = DB::select("SHOW COLUMNS FROM users WHERE Field = 'id'")[0];
    if (strpos($col->Type, 'bigint') !== false) {
        if (strpos($col->Type, 'unsigned') !== false) {
            $table->unsignedBigInteger('user_id');
        } else {
            $table->bigInteger('user_id');
        }
    } else {
        if (strpos($col->Type, 'unsigned') !== false) {
            $table->unsignedInteger('user_id');
        } else {
            $table->integer('user_id');
        }
    }

    $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
    $table->string('phone', 40)->nullable();
    $table->text('address')->nullable();
    $table->timestamps();
});

echo "Guardians table recreated successfully.\n";
