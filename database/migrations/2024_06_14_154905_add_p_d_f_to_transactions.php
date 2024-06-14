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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('payment_method')->nullable();
            $table->foreignId('recipient_id')->nullable()->constrained('users')->onDelete('cascade'); // For transfer recipient
            $table->string('bank_account')->nullable(); // For withdraw
            $table->string('pdf_url')->nullable(); // For storing PDF URL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('payment_method');
            $table->dropColumn('recipient_id');
            $table->dropColumn('bank_account');
            $table->dropColumn('pdf_url');
        });
    }
};
