<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sessions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
            $table->integer('course_id')->unsigned();
            $table->integer('professor_id')->unsigned();
            $table->date('begins_on');
            $table->date('ends_on');
            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('professor_id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sessions');
	}

}
