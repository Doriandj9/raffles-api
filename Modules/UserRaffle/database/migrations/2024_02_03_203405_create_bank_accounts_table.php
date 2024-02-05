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
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('taxid');
            $table->string('name_account',200);
            $table->string('account_number',80);
            $table->string('bank_name',280);
            $table->string('qr_image',500)->nullable();
            $table->string('type');
            $table->boolean('is_account_local')->default(true);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');
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
        Schema::dropIfExists('bank_accounts');
    }
};
