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
        Schema::create('department', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users', 'id', 'fk_department_createdby_users_id')->onUpdate('cascade')
                ->onDelete('SET NULL');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id', 'fk_department_updatedby_users_id')->onUpdate('cascade')
                ->onDelete('SET NULL');
            $table->tinyInteger('is_deleted')->default(0);
            $table->datetime('deleted_at')->nullable();
            $table->foreignId('deleted_by')->nullable()->constrained('users', 'id', 'fk_department_deletedby_users_id')->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department');
    }
};
