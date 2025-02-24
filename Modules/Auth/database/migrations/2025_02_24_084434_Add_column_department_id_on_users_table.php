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
        if (!Schema::hasColumn('users', 'department_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('department_id')->nullable()->constrained('department', 'id', 'fk_users_departmentid_department_id')->onUpdate('cascade')
                    ->onDelete('SET NULL');

                // $table->foreignId('category_id')->constrained();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
    }
};
