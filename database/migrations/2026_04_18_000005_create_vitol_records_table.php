<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vitol_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month');
            $table->decimal('quantity', 18, 4);
            $table->string('unit', 50)->default('BBL');
            $table->decimal('fee_rate', 18, 4)->nullable();
            $table->decimal('commission', 18, 4)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['year', 'month'], 'vitol_year_month_unique');
            $table->index('year');
            $table->index('month');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vitol_records');
    }
};
