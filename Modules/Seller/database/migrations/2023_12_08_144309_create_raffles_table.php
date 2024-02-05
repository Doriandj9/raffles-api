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
        Schema::create('raffles', function (Blueprint $table) {
            $table->id();
            $table->string('name',120);
            $table->timestamp('draw_date');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_complete')->default(false);
            $table->text('logo_raffles')->default('logo-raffle.png');
            $table->text('description');
            $table->text('summary')->nullable();
            $table->double('price');
            $table->double('commission_sellers');
            $table->bigInteger('number_tickets');
            $table->text('awards');
              //tablas foraneas 
              $table->unsignedBigInteger("subscriptions_id")->nullable();
              $table->foreign('subscriptions_id')->references('id')->on('subscriptions')->onDelete('RESTRICT');
              $table->string("user_taxid",40);
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
        Schema::dropIfExists('raffles');
    }
};
