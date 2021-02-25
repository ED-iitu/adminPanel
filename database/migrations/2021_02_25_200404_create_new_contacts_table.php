<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('first_phone')->nullable();
            $table->string('second_phone')->nullable();
            $table->string('first_email')->nullable();
            $table->string('second_email')->nullable();
            $table->string('address_kz')->nullable();
            $table->string('address_eu')->nullable();
            $table->string('lang')->default('ru');
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
        Schema::dropIfExists('new_contacts');
    }
}
