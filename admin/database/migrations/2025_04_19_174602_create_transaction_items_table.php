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
    Schema::create('transaction_items', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('transaction_id');
    $table->unsignedBigInteger('document_id');
    $table->decimal('amount', 10, 2);
    $table->timestamps();
    $table->softDeletes();

    $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
    $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
