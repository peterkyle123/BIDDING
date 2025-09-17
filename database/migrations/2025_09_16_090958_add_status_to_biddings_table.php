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
        $table->string('status')->default('Draft')->after('bid_opening');
    });
}

public function down()
{
    Schema::table('biddings', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}

};
