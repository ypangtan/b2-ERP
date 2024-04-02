<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId( 'customer_id' )->constrained('customers')->onUpdate('restrict')->onDelete('cascade');
            $table->foreignId( 'inventory_id' )->constrained('inventories')->onUpdate('restrict')->onDelete('cascade');
            $table->foreignId( 'lead_id' )->constrained('leads')->onUpdate('restrict')->onDelete('cascade');
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
        Schema::dropIfExists('sales');
    }
}
