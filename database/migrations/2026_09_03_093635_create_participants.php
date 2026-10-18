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
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->string('npk')->unique();
            $table->string('name');
            $table->date('birthdate')->nullable();
            $table->date('join_date')->nullable();
            $table->integer('tanggungan')->default(0);
            $table->integer('total_tiket')->default(1);
            $table->string('plant');
            $table->string('type')->nullable();
            $table->enum('status_karyawan', ['CONTRACT', 'PERMANENT']); 
            $table->enum('kendaraan', ['BUS DEPOK', 'BUS', 'MOBIL PRIBADI'])->nullable(); 
            $table->year('tahun_terakhir_menang')->nullable(); 
            $table->string('qr_code')->unique()->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
