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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'api_key')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->foreignId('deleted_by')->nullable()->constrained('users', 'id', 'fk_users_deletedby_users_id')->onUpdate('cascade')
                    ->onDelete('restrict');

                    // $table->foreignId('category_id')->constrained();
                });
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });
    }
};
