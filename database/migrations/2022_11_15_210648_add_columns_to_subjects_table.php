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
        Schema::table('subjects', function (Blueprint $table) {
            $table->string('class_number')->nullable();
            $table->string('class_room_id')->nullable();
            $table->string('stream')->nullable();
            $table->integer('f2f_enrollment_size')->nullable();
            $table->integer('online_enrollment_size')->nullable();
            $table->string('enrollment_year')->nullable();
            $table->text('remarks')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->text('day')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn(['class_number',
                                'class_room_id', 
                                'stream', 
                                'f2f_enrollment_size',
                                'online_enrollment_size',
                                'enrollment_year',
                                'remarks',
                                'start_date',
                                'end_date',
                                'day'

                            ]);
        });
    }
};
