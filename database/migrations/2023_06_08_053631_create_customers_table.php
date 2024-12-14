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
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("name");
            $table->string("email")->unique()->nullable();;
            $table->string("phone")->unique();
            $table->string("address");
            $table->string("city");
            $table->string("shop_name")->nullable();
            $table->string("photo")->nullable();
            $table->string("bank_name")->nullable();
            $table->string("bank_number")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
