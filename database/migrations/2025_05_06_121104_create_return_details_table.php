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
        Schema::create('return_details', function (Blueprint $table) {
            $table->id();
            $table->string("condition");
            $table->string('photo')->nullable();
            $table->text("quantity")->nullable();
            $table->text("notes")->nullable();
            $table->foreignId("item_unit_id")->constrained("item_units")->cascadeOnDelete();
            $table->foreignId("return_request_id")->constrained("return_requests")->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_details');
    }
};
