<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('time_entries', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->constrained()->cascadeOnDelete();
      $table->foreignId('user_id')->constrained()->cascadeOnDelete();
      $table->date('day');
      $table->decimal('hours', 5, 2); // e.g. 0.25, 1.50
      $table->string('time_input'); // raw input "1h 15m" or "0.25"
      $table->text('note')->nullable();
      $table->boolean('locked')->default(false);
      $table->timestamps();
    });
  }

  public function down(): void {
    Schema::dropIfExists('time_entries');
  }
};