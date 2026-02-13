<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            // (Foreign Key linked to users table)
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('address');
            $table->string('phone')->nullable();
            //  image URL
            $table->string('image')->nullable();
            // Operational details
            $table->decimal('min_order_price', 8, 2)->default(0);
            $table->decimal('delivery_fee', 8, 2)->default(0);
            $table->integer('delivery_time')->nullable();
            // Is the restaurant currently open?
            $table->boolean('is_open')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
