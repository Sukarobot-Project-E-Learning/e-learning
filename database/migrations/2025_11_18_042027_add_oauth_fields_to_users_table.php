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
            // Add username column if not exists
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable()->unique()->after('name');
            }
            
            // Add OAuth provider columns if not exists
            if (!Schema::hasColumn('users', 'provider')) {
                $table->string('provider', 50)->nullable()->comment('OAuth provider: google, facebook, etc')->after('email_verified_at');
            }
            
            if (!Schema::hasColumn('users', 'provider_id')) {
                $table->string('provider_id')->nullable()->comment('OAuth provider user ID')->after('provider');
            }
        });
        
        // Add index separately after columns are created
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'provider') && Schema::hasColumn('users', 'provider_id')) {
                $table->index(['provider', 'provider_id'], 'users_provider_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop index first
            if (Schema::hasColumn('users', 'provider') && Schema::hasColumn('users', 'provider_id')) {
                $table->dropIndex('users_provider_index');
            }
            
            if (Schema::hasColumn('users', 'username')) {
                $table->dropUnique(['username']);
                $table->dropColumn('username');
            }
            
            if (Schema::hasColumn('users', 'provider_id')) {
                $table->dropColumn('provider_id');
            }
            
            if (Schema::hasColumn('users', 'provider')) {
                $table->dropColumn('provider');
            }
        });
    }
};
