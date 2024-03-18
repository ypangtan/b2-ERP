<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePackageOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package_orders', function (Blueprint $table) {
            $table->dropColumn('earned_rebate','total_rebate');
        });

        Schema::table('package_orders', function (Blueprint $table) {
            $table->decimal('monthly_buy_back_rate',20,4)->default(0)->after('monthly_buy_back');
            $table->integer('total_released')->default(0)->after('monthly_buy_back_rate');
            $table->integer('buy_back_limit')->default(24)->after('total_released');
            $table->integer('mission_complete')->default(0)->after('total_released');
            $table->integer('mission_incomplete')->default(0)->after('total_released');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('package_orders', function (Blueprint $table) {
            $table->decimal('earned_rebate',20,4)->default(0)->after('monthly_buy_back');
            $table->decimal('total_rebate',20,4)->default(0)->after('earned_rebate');
        });

        Schema::table('package_orders', function (Blueprint $table) {
            $table->dropColumn('monthly_buy_back_rate','total_released','buy_back_limit','mission_complete','mission_incomplete');
        });   
    }
}
