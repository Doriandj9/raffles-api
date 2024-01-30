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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('code',120);
            $table->integer('order');
            $table->text('qr_image')->nullable();
            $table->boolean('is_buy')->default(false);
             //tablas foraneas 
             $table->unsignedBigInteger("raffles_id")->nullable();
             $table->foreign('raffles_id')->references('id')->on('raffles')->onDelete('RESTRICT');
             $table->string("user_taxid",40)->nullable()->unsigned();
             $table->foreign('user_taxid')->references('taxid')->on('users')->onDelete('RESTRICT');
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
        Schema::dropIfExists('tickets');
    }
};
