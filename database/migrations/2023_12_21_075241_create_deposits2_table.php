<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeposits2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('deposits');

        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onUpdate( 'restrict')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('administrators')->onUpdate('restrict')->onDelete('cascade');
            $table->foreignId('rejected_by')->nullable()->constrained('administrators')->onUpdate('restrict')->onDelete('cascade');
            $table->string('reference')->nullable();
            $table->text('remark')->nullable();
            $table->decimal('amount',20,6)->default(0);
            $table->tinyInteger('wallet_type')->default(1);
            $table->tinyInteger('payment_method')->default(1)->comment('1:offline');
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('deposits');
    }
}
