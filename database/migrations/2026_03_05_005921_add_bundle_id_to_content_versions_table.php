<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('content_versions', function (Blueprint $table) {
            $table->uuid('bundle_id')->nullable()->after('id');
            $table->index(['bundle_id', 'entity_type', 'entity_id']);
        });
    }

    public function down(): void
    {
        Schema::table('content_versions', function (Blueprint $table) {
            $table->dropIndex(['bundle_id', 'entity_type', 'entity_id']);
            $table->dropColumn('bundle_id');
        });
    }
};