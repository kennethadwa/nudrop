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
    Schema::create('transactions', function (Blueprint $table) {
    $table->id();
    $table->string('transaction_no', 20)->unique();
    $table->unsignedBigInteger('pickup_request_id');
    $table->unsignedBigInteger('payment_id');
    $table->decimal('amount', 10, 2);
    $table->unsignedBigInteger('verified_by')->nullable();
    $table->timestamp('verified_at')->nullable();
    $table->timestamps();
    $table->softDeletes();

    $table->foreign('pickup_request_id')->references('id')->on('pickup_requests')->onDelete('cascade');
    $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
    $table->foreign('verified_by')->references('id')->on('staff_accounts')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
