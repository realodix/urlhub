<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoftDeleteUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table = config("laravel_user_management.users_table");

        Schema::table($table, function (Blueprint $table) {
            $table->softDeletes();
            $table->dropColumn('status');
        });

        Schema::table($table, function (Blueprint $table) {
            $table->enum('status', ['pending','accepted','blocked','deleted'])->default('pending');
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

        Schema::table($table, function (Blueprint $table) {
            $table->dropColumn('deleted_at');
            $table->dropColumn('status');
        });

        Schema::table($table, function (Blueprint $table) {
            $table->enum('status', ['pending','accepted','blocked'])->default('pending');
        });
    }
}
