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
        Schema::create('dummy', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('name'); // String column (VARCHAR)
            $table->integer('age'); // Integer column
            $table->float('price'); // Float column
            $table->double('lat', 15, 8); // Double column with precision
            $table->boolean('is_active'); // Boolean column (true/false)
            $table->text('description'); // Text column for longer content
            $table->date('birthdate'); // Date column
            $table->time('start_time'); // Time column
            $table->decimal('amount', 8, 2); // Decimal column with precision (8 digits, 2 decimal places)
            $table->json('preferences'); // JSON column (for storing JSON data)
            $table->enum('status', ['pending', 'completed', 'cancelled']); // Enum column
            $table->binary('image'); // Binary column for storing raw data (e.g., image)
            $table->uuid('uuid'); // UUID column (unique identifier)

            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dummy');
    }
};
