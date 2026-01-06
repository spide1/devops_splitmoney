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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->string('paid_by_name');
            $table->decimal('amount', 10, 2);
            $table->string('description')->nullable();
            $table->enum('is_active', ['Y','N'])->default('Y');
            $table->timestamps();

            $table->foreign('group_id')
                  ->references('id')
                  ->on('groups')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::disableForeignKeyConstraints();
    Schema::dropIfExists('expenses');
    Schema::enableForeignKeyConstraints();
    }
};
