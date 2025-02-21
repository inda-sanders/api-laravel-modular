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

        if (!Schema::hasColumn('users', 'client_id')) {
            Schema::table('users', function (Blueprint $table) {

                $table->foreignId('client_id')->nullable()->constrained('oauth_clients', 'id', 'fk_users_clientid_oauthclients_id')->onUpdate('cascade')
                    ->onDelete('restrict');
                // $table->unsignedBigInteger('client_id');
                // $table->index('client_id');
                // $table->foreignId('client_id')
                //     ->constrained('oauth_clients', 'id', 'fk_users_clientid_oauthclients_id') // Custom name here
                //     ->onUpdate('cascade')
                //     ->onDelete('restrict');
                // $table->foreignId('client_id')->constrained();
            });
            // Schema::table('users', function (Blueprint $table) {
            //     $table->foreign('client_id', 'fk_users_clientid_oauthclients_id') // Custom name here
            //         ->references('id')
            //         ->on('oauth_clients')
            //         ->onUpdate('cascade')
            //         ->onDelete('restrict');
            // });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('', function (Blueprint $table) {});
    }
};
