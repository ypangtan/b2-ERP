<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\{
    PackageBonus,
};

class CreateRankingBonusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement( 'UPDATE package_bonuses SET status = 10' );

        Schema::create('ranking_bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ranking_id')->constrained('rankings')->onUpdate('restrict')->onDelete('cascade');
            $table->decimal('percentage',20,4)->default(0);
            $table->tinyInteger('level')->default(1);
            $table->tinyInteger('type')->default(1);
            $table->tinyInteger('status')->default(10);
            $table->timestamps();
        });

        DB::table( 'ranking_bonuses' )->truncate();

        $existing = DB::table( 'rankings' )->count();

        if ( $existing ) {

            DB::table( 'ranking_bonuses' )->insert( [
                [
                    'id' => 1,
                    'ranking_id' => 2,
                    'percentage' => 5,
                    'level' => 1,
                    'type' => 1,
                    'status' => 10
                ],
                [
                    'id' => 2,
                    'ranking_id' => 3,
                    'percentage' => 8,
                    'level' => 1,
                    'type' => 1,
                    'status' => 10
                ],
                [
                    'id' => 3,
                    'ranking_id' => 4,
                    'percentage' => 10,
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
        Schema::dropIfExists('ranking_bonuses');
    }
}
