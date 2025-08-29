<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('biddings', function (Blueprint $table) {
            $table->string('envelope_system')->nullable()->after('lgu_id');
        });
    }

    public function down(): void
    {
        Schema::table('biddings', function (Blueprint $table) {
            $table->dropColumn('envelope_system');
        });
    }
};
