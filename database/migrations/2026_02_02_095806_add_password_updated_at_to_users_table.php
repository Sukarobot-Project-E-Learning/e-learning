<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'password_updated_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('password_updated_at')->nullable()->after('password');
            });
        }

        // Seed existing data
        // Manual users (provider IS NULL) -> password_updated_at = created_at
        // DB::statement("UPDATE users SET password_updated_at = NOW() WHERE provider IS NULL");
            
        // SSO users (provider = 'google') -> password_updated_at = NULL (already default)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('password_updated_at');
        });
    }
};
