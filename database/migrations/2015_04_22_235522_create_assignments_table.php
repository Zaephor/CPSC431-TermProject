<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('assignments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
            $table->integer('session_id')->unsigned()->index();
            $table->integer('student_id')->unsigned()->index();
            $table->string('assignment_code');
            $table->binary('content');
            $table->unique(array('session_id','student_id','assignment_code'));
            $table->float('score')->nullable()->default(null);
            $table->foreign('session_id')->references('id')->on('sessions');
            $table->foreign('student_id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('assignments');
	}

}
