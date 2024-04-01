<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId( 'customer_id' )->constrained('customers')->onUpdate('restrict')->onDelete('cascade');
            $table->foreignId( 'inventory_id' )->constrained('inventories')->onUpdate('restrict')->onDelete('cascade');
            $table->foreignId( 'user_id' )->constrained('administrators')->onUpdate('restrict')->onDelete('cascade');
            $table->string( 'status' )->default(10);
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
        Schema::dropIfExists('leads');
    }
}
