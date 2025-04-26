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
            $table->enum('type',['deposit', 'transfer', 'withdraw', 'topup', 'billing']);
            $table->double('amount');
            $table->enum('status',['cancelled', 'pending', 'success'])->default('success');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('receiver_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
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
