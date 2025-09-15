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
        $table->date('prep_date')->nullable()->after('bid_opening'); 
        // adjust position if needed
    });
}

public function down()
{
    Schema::table('biddings', function (Blueprint $table) {
        $table->dropColumn('prep_date');
    });
}

};
