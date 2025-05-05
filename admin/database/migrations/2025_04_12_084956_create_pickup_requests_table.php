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
    Schema::create('pickup_requests', function (Blueprint $table) {
        $table->id();
        $table->string('reference_no', 20)->unique();
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('document_id');
        $table->timestamp('request_date')->useCurrent();
        
        $table->enum('status', ['pending', 'on process', 'ready for pickup','completed', 'cancelled'])->default('pending');
        $table->tinyInteger('is_paid')->default(1);
        
        $table->date('pickup_date')->nullable();
        $table->text('remarks')->nullable();
        
        $table->timestamps();
        $table->softDeletes();

        // Foreign keys
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');

        // Optional: indexing for better performance on lookups
        $table->index('user_id');
        $table->index('document_id');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pickup_requests');
    }
};
