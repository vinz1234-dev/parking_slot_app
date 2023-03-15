<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slots', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('status')->default(0);
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->integer('user_id')->nullable();
            $table->timestamps();
        });
        $alpha = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
        foreach ($alpha as $alphabet) {
            DB::table('slots')->insert(
                ['name' => strtoupper($alphabet) . '0' . '1']
            );
            DB::table('slots')->insert(
                ['name' => strtoupper($alphabet) . '0' . '2']
            );
            DB::table('slots')->insert(
                ['name' => strtoupper($alphabet) . '0' . '3']
            );
            DB::table('slots')->insert(
                ['name' => strtoupper($alphabet) . '0' . '4']
            );
            DB::table('slots')->insert(
                ['name' => strtoupper($alphabet) . '0' . '5']
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slots');
    }
};
