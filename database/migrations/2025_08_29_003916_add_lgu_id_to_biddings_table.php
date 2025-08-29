<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('biddings', function (Blueprint $table) {
        if (!Schema::hasColumn('biddings', 'lgu_id')) {
            $table->unsignedBigInteger('lgu_id')->nullable()->after('id');
        }
    });

    Schema::table('biddings', function (Blueprint $table) {
        // Add foreign key safely
        $table->foreign('lgu_id')->references('id')->on('lgus')->onDelete('cascade');
    });
}
    public function down(): void
    {
        Schema::table('biddings', function (Blueprint $table) {
            $table->dropForeign(['lgu_id']);
            $table->dropColumn('lgu_id');
        });
    }
};
