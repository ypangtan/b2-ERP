<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId( 'supplier_id' )->constrained('suppliers')->onUpdate('restrict')->onDelete('cascade');
            $table->foreignId( 'inventory_id' )->constrained('inventories')->onUpdate('restrict')->onDelete('cascade');
            $table->decimal( 'quantity' , 5, 0 );
            $table->string( 'remark' )->nullable();
            $table->decimal( 'price' , 5, 2 );
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
        Schema::dropIfExists('purchases');
    }
}
