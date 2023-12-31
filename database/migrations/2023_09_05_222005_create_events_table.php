<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id('event_id');
            $table->unsignedBigInteger('academic_year_id');
            $table->foreign('academic_year_id')->references('academic_year_id')->on('academic_years')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('event_venue_id')->default(0);
            $table->unsignedBigInteger('event_type_id')->default(0);
            $table->string('if_others', 100)->nullable();
            $table->string('event')->nullable();
            $table->text('event_description')->default('');
            $table->text('event_content')->default('');
            $table->date('event_date_from')->nullable();
            $table->date('event_date_to')->nullable();
            $table->time('event_time_from')->nullable();
            $table->time('event_time_to')->nullable();
            $table->string('img_path')->default('');
            $table->string('file_path')->default('');
            $table->tinyInteger('approval_status')->default(0);
            $table->tinyInteger('is_open')->default(0);
            $table->tinyInteger('is_need_approval')->default(0);

            $table->string('approve_by', 50)->nullable();
            $table->unsignedBigInteger('approve_by_id')->nullable()
                ->default(0);


            $table->tinyInteger('is_archive')->default(0);
                    $table->date('archive_date')->nullable();

            $table->unsignedBigInteger('ao_user_id')->nullable()
                ->default(0);
                
            $table->string('remarks_decline')->nullable();


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
        Schema::dropIfExists('events');
    }
}
