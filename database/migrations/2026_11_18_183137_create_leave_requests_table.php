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
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('kategori', ['Izin', 'Sakit', 'Keperluan Mendesak']);
            $table->text('alasan');
            $table->string('lampiran')->nullable();
            $table->string('status')->default('Disetujui');
            $table->timestamps();
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->string('status')->default('hadir')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
