<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'superapps';

    public function up()
    {
        Schema::table('dapodik_data_pokok', function (Blueprint $table) {
            $table->string('nama_satuan_pendidikan')->nullable()->change();
            $table->string('npsn')->nullable()->change();
            $table->string('bentuk_pendidikan')->nullable()->change();
            $table->string('status_sekolah')->nullable()->change();
            $table->string('alamat', 500)->nullable()->change();
            $table->string('desa')->nullable()->change();
            $table->string('kecamatan')->nullable()->change();
            $table->string('kabupaten_kota')->nullable()->change();
            $table->string('lintang')->nullable()->change();
            $table->string('bujur')->nullable()->change();
            $table->date('tmt_akreditasi')->nullable()->change();
            $table->string('akreditasi', 3)->nullable()->change();
            $table->integer('rombel_t1')->nullable()->change();
            $table->integer('rombel_t2')->nullable()->change();
            $table->integer('rombel_t3')->nullable()->change();
            $table->integer('rombel_t4')->nullable()->change();
            $table->integer('rombel_t5')->nullable()->change();
            $table->integer('rombel_t6')->nullable()->change();
            $table->integer('rombel_t7')->nullable()->change();
            $table->integer('rombel_t8')->nullable()->change();
            $table->integer('rombel_t9')->nullable()->change();
            $table->integer('rombel_t10')->nullable()->change();
            $table->integer('rombel_t11')->nullable()->change();
            $table->integer('rombel_t12')->nullable()->change();
            $table->integer('rombel_t13')->nullable()->change();
            $table->integer('rombel_tka')->nullable()->change();
            $table->integer('rombel_tkb')->nullable()->change();
            $table->integer('rombel_pkta')->nullable()->change();
            $table->integer('rombel_pktb')->nullable()->change();
            $table->integer('rombel_pktc')->nullable()->change();
            $table->integer('jumlah_rombel')->nullable()->change();
            $table->integer('peserta_didik_baru')->nullable()->change();
            $table->integer('tka_l')->nullable()->change();
            $table->integer('tka_p')->nullable()->change();
            $table->integer('tkb_l')->nullable()->change();
            $table->integer('tkb_p')->nullable()->change();
            $table->integer('t1_l')->nullable()->change();
            $table->integer('t1_p')->nullable()->change();
            $table->integer('t2_l')->nullable()->change();
            $table->integer('t2_p')->nullable()->change();
            $table->integer('t3_l')->nullable()->change();
            $table->integer('t3_p')->nullable()->change();
            $table->integer('t4_l')->nullable()->change();
            $table->integer('t4_p')->nullable()->change();
            $table->integer('t5_l')->nullable()->change();
            $table->integer('t5_p')->nullable()->change();
            $table->integer('t6_l')->nullable()->change();
            $table->integer('t6_p')->nullable()->change();
            $table->integer('t7_l')->nullable()->change();
            $table->integer('t7_p')->nullable()->change();
            $table->integer('t8_l')->nullable()->change();
            $table->integer('t8_p')->nullable()->change();
            $table->integer('t9_l')->nullable()->change();
            $table->integer('t9_p')->nullable()->change();
            $table->integer('t10_l')->nullable()->change();
            $table->integer('t10_p')->nullable()->change();
            $table->integer('t11_l')->nullable()->change();
            $table->integer('t11_p')->nullable()->change();
            $table->integer('t12_l')->nullable()->change();
            $table->integer('t12_p')->nullable()->change();
            $table->integer('t13_l')->nullable()->change();
            $table->integer('t13_p')->nullable()->change();
            $table->integer('jumlah_ruang_kelas')->nullable()->change();
            $table->integer('guru')->nullable()->change();
            $table->integer('tendik')->nullable()->change();
        });
    }

    public function down()
    {
        // Tidak perlu mengembalikan ke not null, biarkan default
    }
}; 