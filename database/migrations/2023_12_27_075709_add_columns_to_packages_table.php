<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\{
    Package,
};

class AddColumnsToPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->decimal('min_price',16,2)->default(0)->after('price');
            $table->decimal('max_price',16,2)->default(0)->after('min_price');
        });

        $update = [
            '1' => [
                'min' => 1000,
                'max' => 29999,
            ],
            '2' => [
                'min' => 30000,
                'max' => 49999,
            ],
            '3' => [
                'min' => 50000,
                'max' => 0,
            ],
        ];

        foreach( Package::get() as $package ) {

            $package->min_price = $update[$package->id]['min'];
            $package->max_price = $update[$package->id]['max'];
            $package->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('min_price','max_price');
        });
    }
}
