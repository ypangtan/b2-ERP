<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\{
    Mission,
};

class AddKeyToMissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('missions', function (Blueprint $table) {
            $table->string('key')->after('id')->nullable();
        });

        $deposit = Mission::where( 'title', 'LIKE', '%Monthly Deposit%' )->first();
        if ( $deposit ) {
            $deposit->key = 'monthly_deposit';
            $deposit->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('missions', function (Blueprint $table) {
            $table->dropColumn('key');
        });
    }
}
