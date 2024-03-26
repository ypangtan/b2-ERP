<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId( 'customer_id' )->nullable()->constrained('customers')->onUpdate('restrict')->onDelete('cascade');
            $table->foreignId( 'inventory_id' )->nullable()->constrained('inventories')->onUpdate('restrict')->onDelete('cascade');
            $table->string( 'comment' )->nullable();
            $table->string( 'rating' )->nullable();
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
        Schema::dropIfExists('comments');
    }
}