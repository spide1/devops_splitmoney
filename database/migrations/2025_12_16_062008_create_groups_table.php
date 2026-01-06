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
         Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('is_active', ['Y','N'])->default('Y');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::disableForeignKeyConstraints();
    Schema::dropIfExists('groups');
    Schema::enableForeignKeyConstraints();
    }
};
