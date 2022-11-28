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
        Schema::create('discounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->tinyInteger('discount_type')->default('1')->comments('1-Percentage, 2-Fixed Amount');
            $table->tinyInteger('discount_rule')->default('1')->comments('1-Single, 2-Multiple');
            $table->decimal('discount_amount', 7,2);
            $table->tinyInteger('frequency')->default('1')->comments('1-Monthly, 2-Once');
            $table->integer('enrolment_year');
            // $table->integer('branch_id');
            // $table->integer('level_id');
            // $table->integer('course_id');
            $table->date('earliest_commencement_start_date')->nullable();
            $table->date('earliest_commencement_end_date')->nullable();
            $table->date('course_commencement_start_date')->nullable();
            $table->date('course_commencement_end_date')->nullable();
            $table->date('invoice_commencement_start_date')->nullable();
            $table->date('invoice_commencement_end_date')->nullable();
            $table->text('remarks')->nullable();
            $table->tinyInteger('status')->default('1')->comments('1-Active, 2-Inactive');
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
        Schema::dropIfExists('discounts');
    }
};
