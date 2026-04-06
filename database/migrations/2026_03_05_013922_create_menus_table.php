<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {

            $table->id();

            $table->string('label_id');
            $table->string('label_en');

            $table->string('type'); 
            // page | news | external

            $table->unsignedBigInteger('page_id')->nullable();
            $table->unsignedBigInteger('news_id')->nullable();

            $table->string('url')->nullable();

            $table->integer('sort_order')->default(0);

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};