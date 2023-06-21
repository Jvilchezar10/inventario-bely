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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proof_of_payments_id');
            $table->string('voucher_number');
            $table->unsignedBigInteger('employee_id');
            $table->string('purchase_code');
            $table->date('purchase_date');
            $table->unsignedBigInteger('provider_id');
            $table->decimal('total', 8, 2);
            $table->timestamps();

            $table->foreign('proof_of_payments_id')->references('id')->on('proof_of_payments')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchas');
    }
};
