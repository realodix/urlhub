<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table = config("laravel_user_management.users_table");
        Schema::create($table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable()->unique();
            $table->string('mobile')->nullable()->unique();
            $table->string('password');
            $table->enum('status',['pending','accepted','blocked'])->default('pending');
            $table->boolean('email_verified')->default(false);
            $table->boolean('mobile_verified')->default(false);
            $table->rememberToken();
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
        $table = config("laravel_user_management.users_table");
        Schema::dropIfExists('users');
    }

    private function createTable(array $data)
    {
        foreach($data as $item)
        {
            

        }
    }
}
