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
Schema::create('tasks', function (Blueprint $table) {
$table->id();
$table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key
$table->string('name');
$table->text('description'); // 🔹 Changed from string() to text()
$table->enum('priority', ['low', 'medium', 'high'])->default('medium'); // 🔹 Use enum() instead of string()
$table->timestamps();
});
}

/**
* Reverse the migrations.
*/
public function down(): void
{
Schema::dropIfExists('tasks');
}
};
