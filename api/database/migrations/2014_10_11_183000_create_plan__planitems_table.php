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
            $table->uuid('plan_uuid');
            $table->foreign('plan_uuid')->references('uuid')->on('plans')->onUpdate('cascade')->onDelete('restrict');
            $table->uuid('planitem_uuid');
            $table->foreign('planitem_uuid')->references('uuid')->on('planitems')->onUpdate('cascade')->onDelete('restrict');
            $table->string('value');
            $table->unique(['plan_uuid', 'planitem_uuid']);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE plan__planitems ALTER uuid SET DEFAULT (uuid())');

        
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
