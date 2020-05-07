<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('websites', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('protocol');
            $table->string('address');
            $table->boolean('verified_website')->default(false);
            $table->timestamp('website_verified_at')->nullable();
            $table->boolean('status')->default(true);
            $table->uuid('user_uuid');
            $table->foreign('user_uuid')->references('uuid')->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
            $table->unique(['protocol','address', 'user_uuid']);

        });

        DB::statement('ALTER TABLE websites ALTER uuid SET DEFAULT (uuid())');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('websites');
    }
}
