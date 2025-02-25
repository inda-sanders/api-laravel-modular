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
        Schema::create('regex_validation_rules', function (Blueprint $table) {
            $table->id();
            $table->string('rules_name'); // Name of the rule (optional, for identification)
            $table->string('pattern'); // The regular expression itself
            $table->string('message')->nullable(); // Optional description of the rule
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regex_validation_rules');
    }
};
