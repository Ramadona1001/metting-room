<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meeting_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->integer('capacity')->default(10);
            $table->string('qr_token')->unique();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('max_booking_duration')->default(120); // minutes
            $table->time('working_hours_start')->nullable();
            $table->time('working_hours_end')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meeting_rooms');
    }
};

