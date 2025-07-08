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
        $table->uuid('qrcode_id')->nullable()->unique();
        $table->uuid('nfc_id')->nullable()->unique();
        $table->decimal('amount', 10, 2);
        $table->string('currency', 3);
        $table->string('status')->default('pending');
        $table->timestamp('expires_at')->nullable();
        $table->unsignedBigInteger('user_id')->nullable(); // commerçant
        $table->unsignedBigInteger('payer_id')->nullable(); // client
        $table->timestamp('paid_at')->nullable();
        $table->string('type')->nullable(); // qrcode ou nfc
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
