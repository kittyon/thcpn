<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationroleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oroles', function (Blueprint $table) {
            //
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
        Schema::create('orole_organization', function (Blueprint $table) {
            $table->integer('organization_id')->unsigned();
            $table->integer('orole_id')->unsigned();

            $table->foreign('organization_id')->references('id')->on('organizations')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('orole_id')->references('id')->on('oroles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['organization_id', 'orole_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oroles');
        Schema::dropIfExists('orole_organization');
    }
}
