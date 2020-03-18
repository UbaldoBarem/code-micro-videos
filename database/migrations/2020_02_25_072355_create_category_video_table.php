<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryVideoTable extends Migration
{
    public function up()
    {
        Schema::create('category_video', function (Blueprint $table) {

            $table->engine = 'InnoDB';

            $table->uuid('category_id')->index();
            $table->foreign('category_id')->references('id')->on('categories');

            $table->uuid('video_id')->index();
            $table->foreign('video_id')->references('id')->on('videos');

            $table->unique(['category_id', 'video_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('category_video');
    }
}
