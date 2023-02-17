<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Next year (2024), it should be lumped together with 'password_resets' and removed.
 *
 * https://github.com/laravel/laravel/commit/a28ad2966df4488c873f793c6e5b03cbea547d1e
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('password_resets', 'password_reset_tokens');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            //
        });
    }
};
