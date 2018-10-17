<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceOrganizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_organization', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('organization_id')->unsigned();
          $table->integer('device_id')->unsigned();

          $table->foreign('organization_id')->references('id')->on('organizations')
              ->onUpdate('cascade')->onDelete('cascade');
          $table->foreign('device_id')->references('id')->on('devices')
              ->onUpdate('cascade')->onDelete('cascade');
          $table->softDeletes();
          $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
          $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
          //$table->primary(['organization_id', 'device_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_organization');
    }
}
