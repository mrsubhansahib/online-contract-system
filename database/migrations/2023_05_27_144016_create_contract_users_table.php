<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('contracts', function (Blueprint $table) {
        //     $table->dropIfExists('user_id');
        // });
        Schema::create('contract_users', function (Blueprint $table) {
            $table->id();
            $table->integer('contract_id');
            $table->integer('user_id');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_users');
        Schema::table('contracts', function (Blueprint $table) {
            $table->integer('user_id')->after('title');
        });
    }
}
