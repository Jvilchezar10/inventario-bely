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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proof_of_payment_id');
            $table->string('voucher_number');
            $table->unsignedBigInteger('employee_id');
            $table->string('sales_code');
            $table->date('sales_date');
            $table->unsignedBigInteger('client_id');
            $table->decimal('total', 8, 2);
            $table->timestamps();

            $table->foreign('proof_of_payment_id')->references('id')->on('proof_of_payments')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
