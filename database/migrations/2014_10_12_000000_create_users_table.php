<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('username')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('api_token')->nullable();
            $table->foreignId('role_id')->constrained('roles')->default(2);
            $table->integer('user_level')->default(0); //1 for ADMIN, 0 for USER
            $table->integer('is_active')->default(1);
            $table->integer('is_verified')->default(0);
            $table->string('verification_code', 40);
            $table->string('wallet_balance', 40)->default(0);
            $table->string('wallet_type')->default('PREPAID'); //wallet type can be prepaid or postpaid
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
