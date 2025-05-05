<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::create('payments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('pickup_request_id')->constrained()->onDelete('cascade');
    $table->enum('request_type', ['pickup', 'delivery']);
    $table->decimal('amount', 10, 2);
    $table->foreignId('payment_method_id')->nullable()->constrained()->onDelete('set null');
    $table->timestamp('paid_at')->default(DB::raw('CURRENT_TIMESTAMP'));
    $table->string('proof_of_payment')->nullable();
    $table->boolean('is_verified')->default(false);
    $table->text('verification_remarks')->nullable();
    
    $table->timestamps();
    $table->softDeletes();

    // Indexes
    $table->index('request_type');
    $table->index('pickup_request_id');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
