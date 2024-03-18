<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePackageBonusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages')->onUpdate('restrict')->onDelete('cascade');
            $table->decimal('percentage',20,4)->default(0);
            $table->tinyInteger('level')->default(1);
            $table->tinyInteger('type')->default(1);
            $table->tinyInteger('status')->default(10);
            $table->timestamps();
        });

        DB::table( 'package_bonuses' )->truncate();

        $existing = DB::table( 'packages' )->count();

        if ( $existing ) {

            DB::table( 'package_bonuses' )->insert( [
                [
                    'id' => 1,
                    'package_id' => 1,
                    'percentage' => 24,
                    'level' => 1,
                    'type' => 1,
                    'status' => 10
                ],
                [
                    'id' => 2,
                    'package_id' => 2,
                    'percentage' => 31.2,
                    'level' => 1,
                    'type' => 1,
                    'status' => 10
                ],
                [
                    'id' => 3,
                    'package_id' => 3,
                    'percentage' => 36,
                    'level' => 1,
                    'type' => 1,
                    'status' => 10
                ],
            ] );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_bonuses');
    }
}
