<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('taxid',40)->unique('uk_taxid')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('username',40)->nullable();
            $table->integer('permission')->default(0);
            $table->string('rol',50)->nullable();
            $table->string('phone',20);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_new')->default(true);
            $table->boolean('is_pending')->default(false);
            $table->boolean('is_client')->default(false);
            $table->boolean('is_raffles')->default(false);
            $table->boolean('is_seller')->default(false);
            $table->boolean('is_admin')->default(false);
            $table->boolean('organize_riffs')->default(false);
            $table->date('start_date_supcription')->nullable();
            $table->date('end_date_suscription')->nullable();
            $table->integer('remaining_days_suscription')->default(0);
            $table->text('token')->nullable();
            $table->text('nationality')->nullable();
            $table->text('address')->nullable();
            $table->text('avatar')->nullable();
            $table->boolean('send_email')->default(true);
            $table->boolean('platform_notifications')->default(true);
            $table->string('verify_photo')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        $this->createAdmin();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }

    public function createAdmin(){
        $data = [
            'first_name' => 'Admin',
            'last_name' => '24 Hayu',
            'username' => 'admin',
            'phone' => '0985145768',
            'taxid' => '0250758874',
            'email' => 'admin@email.com',
            'email_verified_at' => now(),
            'password' => Hash::make('1234'),
            'remember_token' => Str::random(10),
            'is_admin' => true
        ];
        User::create($data);
    }
};
