<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('damaged_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_unit_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->unsigned();
            $table->date('damaged_at');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('damaged_items');
    }
};
