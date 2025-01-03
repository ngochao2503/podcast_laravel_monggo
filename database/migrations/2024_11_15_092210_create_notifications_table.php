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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('sender_id');
            $table->string('receiver_id');
            $table->string('podcast_id');
            $table->string('content');
            $table->timestamps();
            $table->boolean('is_seen');


            $table->foreign('podcast_id')
                // ->references('id')
                ->on('podcasts')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('sender_id')
                // ->references('id')
                ->on('podcasters')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('receiver_id')
                // ->references('id')
                ->on('podcasters')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
