<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
      public function up()
    {
        Schema::table('biddings', function (Blueprint $table) {
            $table->dateTime('pre_bid')->nullable()->change();
            $table->dateTime('bid_submission')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('biddings', function (Blueprint $table) {
            $table->dateTime('pre_bid')->nullable(false)->change();
            $table->dateTime('bid_submission')->nullable(false)->change();
        });
    }
};
