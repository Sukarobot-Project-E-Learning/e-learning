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
        Schema::table('program_proofs', function (Blueprint $table) {
            $table->integer('rating')->nullable()->comment('Rating from 1 to 5');
            $table->text('review')->nullable()->comment('User review text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_proofs', function (Blueprint $table) {
            $table->dropColumn(['rating', 'review']);
        });
    }
};
