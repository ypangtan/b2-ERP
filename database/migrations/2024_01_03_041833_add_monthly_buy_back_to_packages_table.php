<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\{
    Package
};

class AddMonthlyBuyBackToPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->decimal('monthly_buy_back',16,4)->default(0)->after('max_price');
        });

        $packages = Package::get();

        $mbb = [ 1, 1.3, 1.5 ];

        foreach ( $packages as $key => $package ) {
            $package->monthly_buy_back = $mbb[$key];
            $package->save();
        }

        Schema::table('package_orders', function (Blueprint $table) {
            $table->decimal('monthly_buy_back',16,4)->default(0)->after('amount');
            $table->decimal('earned_rebate',20,4)->default(0)->after('monthly_buy_back');
            $table->decimal('total_rebate',20,4)->default(0)->after('earned_rebate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('monthly_buy_back');
        });

        Schema::table('package_orders', function (Blueprint $table) {
            $table->dropColumn('monthly_buy_back','earned_rebate','total_rebate');
        });
    }
}
