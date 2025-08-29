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
        $table->string('reference_number')->nullable()->after('lgu_id');
        $table->string('delivery_schedule')->nullable()->after('reference_number');
    });
}


public function down()
{
    Schema::table('biddings', function (Blueprint $table) {
        $table->dropColumn(['reference_number', 'delivery_schedule']);
    });
}

};
