<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('urls', function (Blueprint $table) {
            $table->dateTime('expires_at')->nullable();
            $table->integer('expired_clicks')->nullable();
            $table->longText('expired_url')->nullable();
            $table->text('expired_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('urls', function (Blueprint $table) {
            $table->dropColumn('expires_at');
            $table->dropColumn('expired_clicks');
            $table->dropColumn('expired_url');
            $table->dropColumn('expired_notes');
        });
    }
};
