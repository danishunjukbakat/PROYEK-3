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
        Schema::create('activities', function (Blueprint $table) {
    $table->id();

    $table->foreignId('category_id')
          ->constrained()
          ->restrictOnDelete();

    $table->string('code', 30)->unique();
    $table->string('title', 150);
    $table->text('description')->nullable();

    $table->dateTime('start_at');
    $table->dateTime('end_at');

    $table->string('location', 150);
    $table->unsignedInteger('capacity');

    $table->string('status', 20)->default('draft');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
