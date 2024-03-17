<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('news', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('author')->nullable();
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->text('url')->nullable();
            $table->text('url_to_image')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->text('content')->nullable();

            $table->bigInteger('source_id')->unsigned();
            $table->foreign('source_id')
                ->references('id')->on('sources')
                ->onDelete('cascade');


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
        Schema::dropIfExists('news');
    }
};
