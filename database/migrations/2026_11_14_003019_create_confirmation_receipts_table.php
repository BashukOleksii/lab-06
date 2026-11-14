<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('confirmation_receipts', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('bike_rack_id')
                ->unique()
                ->constrained('bike_racks')
                ->cascadeOnDelete();

            $table->string('summary');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('confirmation_receipts');
    }
};
