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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username');
            $table->string('password');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->enum("role", ["user", "super-admin", "staff"]);
            $table->foreignId('origin_id')->nullable()->constrained('origins')->nullOnDelete();
            $table->timestamp('last_logined_at')->nullable();
            $table->boolean('active')->default(false);
            $table->string('profile_picture')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
