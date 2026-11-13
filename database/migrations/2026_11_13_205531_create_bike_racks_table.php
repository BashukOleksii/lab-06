<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bike_racks', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('owner_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('district_id')
                ->constrained('districts')
                ->restrictOnDelete();

            $table->string('title', 120);
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('slot_count');
            $table->date('scheduled_opening_on');
            $table->enum('status', ['draft', 'confirmed'])->default('draft');
            $table->timestamps();

            $table->index(['owner_id', 'district_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bike_racks');
    }
};
