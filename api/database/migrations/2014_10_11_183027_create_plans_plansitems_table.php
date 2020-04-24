<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansPlansitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans_plansitems', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('plan_id');
            $table->foreign('plan_id')->references('id')->on('plans')->onUpdate('cascade')->onDelete('restrict');
            $table->unsignedBigInteger('planitems_id');
            $table->foreign('planitems_id')->references('id')->on('plansitems')->onUpdate('cascade')->onDelete('restrict');
            $table->string('value');
            $table->unique('plan_id', 'planitems_id');
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('plans_plansitems');
    }
}
