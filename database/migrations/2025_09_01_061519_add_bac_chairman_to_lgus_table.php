<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('lgus', function (Blueprint $table) {
        $table->string('bac_chairman')->nullable()->after('envelope_system');
    });
}

public function down(): void
{
    Schema::table('lgus', function (Blueprint $table) {
        $table->dropColumn('bac_chairman');
    });
}
};
