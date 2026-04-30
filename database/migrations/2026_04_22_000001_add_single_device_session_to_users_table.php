<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'active_session_id')) {
                $table->string('active_session_id', 120)->nullable()->after('is_active');
            }

            if (! Schema::hasColumn('users', 'active_login_at')) {
                $table->timestamp('active_login_at')->nullable()->after('active_session_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'active_login_at')) {
                $table->dropColumn('active_login_at');
            }

            if (Schema::hasColumn('users', 'active_session_id')) {
                $table->dropColumn('active_session_id');
            }
        });
    }
};