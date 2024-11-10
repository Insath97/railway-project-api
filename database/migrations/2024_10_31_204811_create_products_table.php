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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_category_id')->constrained('main_categories')->onDelete('cascade');
            $table->foreignId('sub_category_id')->constrained('sub_categories')->onDelete('cascade');
            $table->foreignId('unitType_id')->constrained('unit_types');
            $table->string('code')->unique();
            $table->string('name');
            $table->string('color');
            $table->decimal('size', 8, 2)->nullable();
            $table->decimal('low_stock_threshold', 8, 2)->default(0);
            $table->text('description')->nullable();
            $table->boolean('delete_status')->default(1); // 1 => active, 0 => deleted
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
