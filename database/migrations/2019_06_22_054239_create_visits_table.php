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
        Schema::create('visits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('url_id')->constrained()->cascadeOnDelete();
            $table->string('user_type', 10)->nullable();
            $table->boolean('is_first_click');
            $table->string('referer', 300)->nullable();
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->string('user_uid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
