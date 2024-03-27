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
            //tablas foraneas 
            $table->unsignedBigInteger("commissions_id")->nullable();
            //$table->foreign('commissions_id')->references('id')->on('commissions')->onDelete('CASCADE');
            $table->unsignedBigInteger("tickets_id")->nullable();
            //$table->foreign('tickets_id')->references('id')->on('tickets')->onDelete('no action');
            $table->boolean('is_sales_code')->default(false);
            $table->boolean('is_complete')->default(false);
            $table->double('value');

            $table->index('tickets_id','idx_code');


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
        Schema::dropIfExists('sales');
    }
};
