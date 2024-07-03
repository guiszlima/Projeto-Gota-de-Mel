<<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('permission')->unique();
            $table->timestamps();
        });

        // Inserir os registros
        DB::table('roles')->insert([
            ['name' => 'Vendedor', 'permission' => 1],
            ['name' => 'Gerente', 'permission' => 2],
            ['name' => 'Administrador', 'permission' => 3],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
