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
        Schema::create('urls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();
            $table->string('user_type', 10)->nullable();

            if (Schema::getConnection()->getConfig('driver') === 'mysql') {
                $table->string('keyword')
                    ->collation('utf8mb4_bin')
                    ->unique();
            } else {
                $table->string('keyword')->unique();
            }

            $table->boolean('is_custom');
            $table->text('destination');
            $table->string('title');
            $table->boolean('forward_query')->default(true);
            $table->string('user_uid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('urls');
    }
};
