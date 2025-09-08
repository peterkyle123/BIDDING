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
        Schema::create('biddings', function (Blueprint $table) {
            $table->id();
            $table->string('project_name');
            $table->decimal('abc', 15, 2); // Approved Budget for the Contract
            $table->dateTime('pre_bid')->nullable();
$table->dateTime('bid_submission')->nullable();
            $table->dateTime('bid_opening');
            $table->foreignId('lgu_id')->constrained('lgus')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biddings');
    }
};
