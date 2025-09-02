<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');                      // document title
            $table->text('description')->nullable();      // document description
            $table->string('file_name');                  // original file name
            $table->string('file_path');                  // storage path
            $table->foreignId('lgu_id')->nullable()->constrained('lgus')->nullOnDelete(); // optional LGU
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
