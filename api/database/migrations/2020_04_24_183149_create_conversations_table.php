<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('bot_id')->unique();
            $table->foreign('bot_id')->references('id')->on('bots')->onUpdate('cascade')->onDelete('restrict');
            $table->longText('value');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE conversations ALTER uuid SET DEFAULT (uuid())');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversations');
    }
}
