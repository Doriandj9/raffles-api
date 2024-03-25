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
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
             //tablas foraneas 
             
             $table->unsignedBigInteger("raffles_id")->nullable();
             $table->foreign('raffles_id')->references('id')->on('raffles')->onDelete('RESTRICT');
             $table->string("user_taxid",40)->nullable()->unsigned();
             $table->foreign('user_taxid')->references('taxid')->on('users')->onDelete('RESTRICT');
             $table->boolean('seller_pos')->default(false);
             $table->string('code');
             $table->string('url');
             $table->string('status',2);
             $table->boolean('is_paid')->default(false);
             $table->string('path_qr')->nullable();

             //indeces 
             $table->index('code','idx_code');
            
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
        Schema::dropIfExists('commissions');
    }
};
