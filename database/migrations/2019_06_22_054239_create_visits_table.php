<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('url_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();
            $table->string('visitor_id');
            $table->boolean('is_first_click');
            $table->string('referer', 300)->nullable()->default(0);
            $table->ipAddress('ip');
            $table->string('browser')->nullable();
            $table->string('browser_version')->nullable();
            $table->string('device')->nullable();
            $table->string('os')->nullable();
            $table->string('os_version')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visits');
    }
};
