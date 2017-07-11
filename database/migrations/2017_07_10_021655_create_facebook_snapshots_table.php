<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacebookSnapshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facebook_snapshots', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('social_profile_id')->unsigned();
            $table->foreign('social_profile_id')
                ->references('id')
                ->on('social_profiles')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('likes');
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
        if (Schema::hasTable('facebook_snapshots')) {
            Schema::table('facebook_snapshots', function (Blueprint $table) {
                $table->dropForeign(['social_profile_id']);
            });

            Schema::drop('facebook_snapshots');
        }
    }
}
