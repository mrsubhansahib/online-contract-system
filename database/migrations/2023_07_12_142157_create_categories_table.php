<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('added_by');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('contracts', function (Blueprint $table) {
            $table->integer('category_id')->after('title')->nullable();
            $table->string('download_status')->default('Not-Downloaded');
        });
        Schema::table('contract_copies', function (Blueprint $table) {
            $table->integer('category_id')->after('title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });
        Schema::table('contract_copies', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });
    }
}
