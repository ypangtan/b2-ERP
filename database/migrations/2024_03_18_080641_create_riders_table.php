<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riders', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->string('fullname')->nullable();
            $table->string('ic')->nullable();
            $table->string('email')->nullable();
            $table->string('calling_code', 5)->nullable();
            $table->string('phone_number',20)->nullable();
            $table->string('password')->nullable();
            $table->tinyInteger('status')->default(10);
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
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
        Schema::dropIfExists('riders');
    }
}
