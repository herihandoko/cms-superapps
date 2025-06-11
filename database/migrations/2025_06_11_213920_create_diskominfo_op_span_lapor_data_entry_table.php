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
        Schema::connection('superapps')->create('diskominfo_op_span_lapor_data_entry', function (Blueprint $table) {
            $table->id();
            $table->string('unit_kerja');
            $table->integer('belum_terverifikasi')->default(0);
            $table->integer('belum_ditindaklanjuti')->default(0);
            $table->integer('proses')->default(0);
            $table->integer('selesai')->default(0);
            $table->integer('total')->default(0);
            $table->decimal('persentase_tl', 5, 2)->default(0.00);
            $table->integer('rtl')->default(0);
            $table->integer('rhp')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('superapps')->dropIfExists('diskominfo_op_span_lapor_data_entry');
    }
};
