<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('plans');
            $table->foreignId('user_id')->constrained('users');
            $table->string('amount');
            $table->string('currency');
            $table->string('payment_plan');
            $table->dateTime('start_date');
            $table->dateTime('due_date');
            $table->integer('auto_renewal')->default(0); //0 - not set to auto renewal, 1 set to auto renewal
            $table->integer('status')->default(1); //0 for inactive/cancelled, 1 for active
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
        Schema::dropIfExists('subscriptions');
    }
}
