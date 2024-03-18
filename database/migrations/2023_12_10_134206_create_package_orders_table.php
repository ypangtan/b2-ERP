<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages')->onUpdate('restrict')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onUpdate('restrict')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('administrators')->onUpdate('restrict')->onDelete('cascade');
            $table->foreignId('rejected_by')->nullable()->constrained('administrators')->onUpdate('restrict')->onDelete('cascade');
            $table->string('reference')->nullable();
            $table->decimal('amount',16,2)->default(0);
            $table->tinyInteger('type')->default(1);
            $table->tinyInteger('status')->default(10);
            $table->timestamps();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_orders');
    }
}
