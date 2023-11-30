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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->references('id')->on('members');
            $table->integer('total_item');
            $table->integer('total_price');
            $table->tinyInteger('discount')->default(0);
            $table->integer('payment')->default(0);
            $table->integer('received')->default(0);
            $table->foreignId('user_id')->references('id')->on('users');;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
