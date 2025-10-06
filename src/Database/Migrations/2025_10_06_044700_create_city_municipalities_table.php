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
        Schema::create('city_municipalities', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name');
            $table->foreignId('province_id')->nullable()->constrained('provinces')->nullOnDelete();
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->enum('type', ['City','Municipality'])->index();
            $table->string('income_class')->nullable();
            $table->string('urban_rural')->nullable();
            $table->string('old_name')->nullable();
            $table->string('status')->default('active');
            $table->softDeletes();
            $table->timestamps();
            $table->index(['name', 'province_id', 'region_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('city_municipalities');
    }
};
