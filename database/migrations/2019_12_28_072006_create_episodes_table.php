<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episodes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('series_id', 50)->index();
            $table->string('name_ru', 50);
            $table->string('name_en', 50)->nullable();
            $table->string('url');
            $table->tinyInteger('season')->nullable();
            $table->tinyInteger('episode')->nullable();
            $table->string('keywords', 100)->nullable()->index();
            $table->text('meta')->nullable();
            $table->date('released_at')->nullable()->index();
            $table->timestamps();

            $table->foreign('series_id')->references('id')->on('series')
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
        Schema::dropIfExists('episodes');
    }
}
