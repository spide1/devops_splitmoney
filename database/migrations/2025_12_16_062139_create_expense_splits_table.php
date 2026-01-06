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
        Schema::create('expense_splits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expense_id');
            $table->string('member_name');
            $table->decimal('share_amount', 10, 2);
            $table->enum('is_settled', ['Y','N'])->default('N');

            $table->foreign('expense_id')
                  ->references('id')
                  ->on('expenses')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
          Schema::disableForeignKeyConstraints();
    Schema::dropIfExists('expense_splits');
    Schema::enableForeignKeyConstraints();
    }
};
