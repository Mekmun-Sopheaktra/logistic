<?php

use App\Models\Income;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("product_name")->unique();
            $table->text('description')->nullable();
            $table->integer("product_quantity")->default(0);
            $table->enum('unit',['Kilogram', 'Can', 'Pack'])->default('Kilogram');
            $table->string("product_code")->nullable();
            $table->string("product_garage")->nullable();
            $table->string("product_route")->nullable();
            $table->string("product_image")->nullable();
            $table->string("expire_date")->nullable();
            $table->integer("import_price")->nullable();
            $table->integer("export_price")->nullable();
            $table->integer("product_amount")->default(0);
            $table->string('url')->default(0);
            $table->timestamps();
        });
    }
    //total_import

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
