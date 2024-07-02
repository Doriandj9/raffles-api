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
        Schema::create('request_incomes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('raffle_id');
            $table->foreign('raffle_id')->references('id')->on('raffles')->onDelete('RESTRICT');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');
            $table->double('amount');
            $table->text('observation')->nullable();
            $table->string('status',2)->default('DR');
            $table->boolean('is_active')->default(true);
            $table->text('voucher')->nullable();
            //campos de auditoria 
            $table->unsignedBigInteger("created_by")->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('RESTRICT');
            $table->unsignedBigInteger("updated_by")->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('RESTRICT');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_incomes');
    }
};
