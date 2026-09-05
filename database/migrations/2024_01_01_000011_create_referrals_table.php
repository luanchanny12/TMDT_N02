<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->cascadeOnDelete();       // User A (người giới thiệu)
            $table->foreignId('referred_user_id')->constrained('users')->cascadeOnDelete();  // User B (được giới thiệu)
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->string('referral_code');
            $table->unsignedBigInteger('commission')->default(0); // VNĐ
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();

            // Một User B chỉ tạo 1 referral record (chỉ được giới thiệu 1 lần)
            $table->unique('referred_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
