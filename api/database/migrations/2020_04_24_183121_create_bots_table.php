<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->uuid('website_uuid')->unique();
            $table->foreign('website_uuid')->references('uuid')->on('websites')->onUpdate('cascade')->onDelete('restrict');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE bots ALTER uuid SET DEFAULT (uuid())');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bots');
    }
}
