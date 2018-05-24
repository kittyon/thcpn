<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            //
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('permission_urole', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('urole_id')->unsigned();

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('urole_id')->references('id')->on('uroles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'urole_id']);
        });
        Schema::create('permission_orole', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('orole_id')->unsigned();

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('orole_id')->references('id')->on('oroles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'orole_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('permission_urole');
        Schema::dropIfExists('permission_orole');
    }
}
