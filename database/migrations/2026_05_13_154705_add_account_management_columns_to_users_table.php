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
            if (!Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')->nullable()->after('password');
            }

            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('google_id');
            }

            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('pelapor')->after('avatar');
            }

            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('role');
            }

            if (!Schema::hasColumn('users', 'active_session_id')) {
                $table->string('active_session_id')->nullable()->after('is_active');
            }

            if (!Schema::hasColumn('users', 'active_login_at')) {
                $table->timestamp('active_login_at')->nullable()->after('active_session_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'active_login_at')) {
                $table->dropColumn('active_login_at');
            }

            if (Schema::hasColumn('users', 'active_session_id')) {
                $table->dropColumn('active_session_id');
            }

            if (Schema::hasColumn('users', 'is_active')) {
                $table->dropColumn('is_active');
            }

            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }

            if (Schema::hasColumn('users', 'avatar')) {
                $table->dropColumn('avatar');
            }

            if (Schema::hasColumn('users', 'google_id')) {
                $table->dropColumn('google_id');
            }
        });
    }
};