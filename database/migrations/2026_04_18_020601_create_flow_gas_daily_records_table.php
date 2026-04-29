<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flow_gas_daily_records', function (Blueprint $table) {
            $table->id();

            $table->string('type', 50)->default('gas');

            $table->foreignId('flow_gas_category_id')
                ->constrained('flow_gas_categories')
                ->cascadeOnDelete();
            $table->date('record_date');
            $table->decimal('mscf', 18, 4)->nullable();
            $table->decimal('mmbtu', 18, 4)->nullable();
            $table->decimal('fix', 18, 4)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(
                ['type', 'flow_gas_category_id', 'record_date'],
                'flow_gas_unique_per_day'
            );


            $table->index('record_date');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flow_gas_daily_records');
    }
};