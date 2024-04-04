<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->onUpdate( 'restrict')->onDelete('cascade');
            $table->foreignId('type_id')->nullable()->constrained('types')->onUpdate( 'restrict')->onDelete('cascade');
            $table->string( 'name' )->nullable();
            $table->decimal( 'price' , 5, 2 )->nullable();
            $table->string( 'desc' )->nullable();
            $table->decimal( 'stock' , 4, 0 )->nullable();
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
        Schema::dropIfExists('inventories');
    }
}
