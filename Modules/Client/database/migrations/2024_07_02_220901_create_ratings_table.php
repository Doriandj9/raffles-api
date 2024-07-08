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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');
            $table->float('calification');
            $table->text('comment')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('status',2)->default('CL');
            $table->integer('likes')->default(0);
            $table->integer('not_likes')->default(0);
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
        Schema::dropIfExists('ratings');
    }
};
