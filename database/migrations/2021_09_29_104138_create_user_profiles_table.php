<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('phone_number')->nullable();
            $table->string('occupation')->nullable();
            $table->string('avatar')->default('avatar.png');
            $table->string('cover_photo')->default('cover_photo.png');
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('about')->nullable();
            $table->text('address')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->ipAddress('registered_ip')->nullable();
            $table->ipAddress('registered_device')->nullable();
            $table->integer('is_bvn_verified')->default(0);
            $table->string('account_number_source')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
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
        Schema::dropIfExists('user_profiles');
    }
}
