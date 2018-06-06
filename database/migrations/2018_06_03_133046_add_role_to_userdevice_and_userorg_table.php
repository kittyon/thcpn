<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRoleToUserdeviceAndUserorgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_user', function (Blueprint $table) {
            //
            $table->json('urole_ids');
        });
        Schema::table('organization_user', function (Blueprint $table) {
            //
            $table->json('urole_ids');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('device_user', function (Blueprint $table) {
            //
            $table->dropColumn("urole_ids");
        });
        Schema::table('organization_user', function (Blueprint $table) {
            //
            $table->dropColumn("urole_ids");
        });
    }
}
