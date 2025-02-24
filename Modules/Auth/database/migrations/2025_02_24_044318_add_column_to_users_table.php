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
            $table->datetime('deleted_at')->nullable();
            $table->foreignId('updated_by')->after('updated_at')->nullable()->constrained('users', 'id', 'fk_users_updatedby_users_id')->onUpdate('cascade')
            ->onDelete('SET NULL');
            $table->foreignId('created_by')->after('created_at')->nullable()->constrained('users', 'id', 'fk_users_createdby_users_id')->onUpdate('cascade')
            ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['updated_by','created_by']);
            $table->dropColumn('updated_by');
            $table->dropColumn('created_by');
        });
    }
};
