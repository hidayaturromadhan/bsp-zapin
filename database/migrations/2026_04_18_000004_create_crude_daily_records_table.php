<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crude_daily_records', function (Blueprint $table) {
            $table->id();
            $table->date('record_date')->unique();
            $table->decimal('production', 18, 4);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('record_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crude_daily_records');
    }
};
