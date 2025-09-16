<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('month_closures', function (Blueprint $table) {
      $table->id();
      $table->integer('year');
      $table->integer('month'); 
      $table->json('summary'); 
      $table->foreignId('closed_by')->constrained('users')->nullOnDelete();
      $table->timestamps();
     /*  $table->unique(['year','month']); */
    });
  }

  public function down(): void {
    Schema::dropIfExists('month_closures');
  }
};