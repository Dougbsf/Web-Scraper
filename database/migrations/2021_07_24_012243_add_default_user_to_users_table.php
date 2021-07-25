<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultUserToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $pass = Hash::make('admin');

        DB::table('users')->insert(
            array(
                'name' => 'Administrador do Sistema',
                'email' => 'admin@admin.com',
                'password' => $pass
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('users')->delete(
            array(
                'email' => 'admin@admin.com',
            )
        );
    }
}
