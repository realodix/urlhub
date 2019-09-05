<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDepartmentUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $departments_table  = config("laravel_user_management.user_department_table");
        $users_table        = config("laravel_user_management.users_table");
        $table              = config("laravel_user_management.user_department_user_table");

        Schema::create($table, function (Blueprint $table) use($departments_table,$users_table)
        {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('department_id');

            $table->foreign('department_id')
                ->references('id')
                ->on($departments_table)
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('user_id')
                ->references('id')
                ->on($users_table)
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table = config("laravel_user_management.user_department_user_table");
        Schema::dropIfExists($table);
    }
}
