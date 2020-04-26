<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanPlanitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan__planitems', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('plan_id');
            $table->foreign('plan_id')->references('id')->on('plans')->onUpdate('cascade')->onDelete('restrict');
            $table->unsignedBigInteger('planitem_id');
            $table->foreign('planitem_id')->references('id')->on('planitems')->onUpdate('cascade')->onDelete('restrict');
            $table->string('value');
            $table->unique(['plan_id', 'planitem_id']);
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
        Schema::dropIfExists('plan__planitems');
    }
}
