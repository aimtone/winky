<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->unique();
            $table->string('name')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->uuid('user_uuid')->nullable();
            $table->uuid('plan_uuid')->default('96f749f5-88d8-11ea-9e87-9828a60067ab');
            $table->foreign('plan_uuid')->references('uuid')->on('plans')->onUpdate('cascade')->onDelete('restrict');
            $table->boolean('status')->default(true);
            $table->uuid('email_verification_key')->unique();
            $table->rememberToken();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE users ALTER uuid SET DEFAULT (uuid())');

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('user_uuid')->references('uuid')->on('users')->onUpdate('cascade')->onDelete('restrict');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
