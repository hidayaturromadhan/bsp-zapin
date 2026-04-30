<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')->nullable()->unique()->after('id');
            }

            if (! Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('email');
            }

            if (! Schema::hasColumn('users', 'active_session_id')) {
                if (Schema::hasColumn('users', 'role')) {
                    $table->string('active_session_id')->nullable()->after('role');
                } else {
                    $table->string('active_session_id')->nullable()->after('password');
                }
            }

            if (! Schema::hasColumn('users', 'active_login_at')) {
                $table->timestamp('active_login_at')->nullable()->after('active_session_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'google_id')) {
                $table->dropUnique(['google_id']);
                $table->dropColumn('google_id');
            }

            if (Schema::hasColumn('users', 'avatar')) {
                $table->dropColumn('avatar');
            }

            if (Schema::hasColumn('users', 'active_login_at')) {
                $table->dropColumn('active_login_at');
            }

            if (Schema::hasColumn('users', 'active_session_id')) {
                $table->dropColumn('active_session_id');
            }
        });
    }
};