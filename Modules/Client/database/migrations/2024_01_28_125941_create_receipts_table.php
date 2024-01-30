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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');
            $table->string('organizer_raffles_taxid',40);
            $table->foreign('organizer_raffles_taxid')->references('taxid')->on('users')->onDelete('RESTRICT');
            $table->text('description');
            $table->double('total');
            $table->double('subtotal');
            $table->integer('amount');
            $table->double('single_price');
            $table->string('voucher');
            $table->boolean('is_active')->default(true);

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
        Schema::dropIfExists('receipts');
    }
};
