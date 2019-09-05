<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table = config('laravel_user_management.user_department_table');
        Schema::create($table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->unsignedInteger('parent_id')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')
                ->references('id')
                ->on($table)
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
        $table = config('laravel_user_management.user_department_table');
        Schema::dropIfExists($table);
    }
}
