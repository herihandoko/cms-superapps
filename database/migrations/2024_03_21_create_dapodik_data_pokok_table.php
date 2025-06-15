<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The database connection that should be used by the migration.
     *
     * @var string
     */
    protected $connection = 'superapps';

    public function up()
    {
        Schema::create('dapodik_data_pokok', function (Blueprint $table) {
            $table->id();
            $table->string('nama_satuan_pendidikan');
            $table->string('npsn');
            $table->string('bentuk_pendidikan');
            $table->string('status_sekolah');
            $table->string('alamat', 500);
            $table->string('desa');
            $table->string('kecamatan');
            $table->string('kabupaten_kota');
            $table->string('lintang');
            $table->string('bujur');
            $table->date('tmt_akreditasi')->nullable();
            $table->string('akreditasi', 3)->nullable();
            $table->integer('rombel_t1')->default(0);
            $table->integer('rombel_t2')->default(0);
            $table->integer('rombel_t3')->default(0);
            $table->integer('rombel_t4')->default(0);
            $table->integer('rombel_t5')->default(0);
            $table->integer('rombel_t6')->default(0);
            $table->integer('rombel_t7')->default(0);
            $table->integer('rombel_t8')->default(0);
            $table->integer('rombel_t9')->default(0);
            $table->integer('rombel_t10')->default(0);
            $table->integer('rombel_t11')->default(0);
            $table->integer('rombel_t12')->default(0);
            $table->integer('rombel_t13')->default(0);
            $table->integer('rombel_tka')->default(0);
            $table->integer('rombel_tkb')->default(0);
            $table->integer('rombel_pkta')->default(0);
            $table->integer('rombel_pktb')->default(0);
            $table->integer('rombel_pktc')->default(0);
            $table->integer('jumlah_rombel')->default(0);
            $table->integer('peserta_didik_baru')->default(0);
            $table->integer('tka_l')->default(0);
            $table->integer('tka_p')->default(0);
            $table->integer('tkb_l')->default(0);
            $table->integer('tkb_p')->default(0);
            $table->integer('t1_l')->default(0);
            $table->integer('t1_p')->default(0);
            $table->integer('t2_l')->default(0);
            $table->integer('t2_p')->default(0);
            $table->integer('t3_l')->default(0);
            $table->integer('t3_p')->default(0);
            $table->integer('t4_l')->default(0);
            $table->integer('t4_p')->default(0);
            $table->integer('t5_l')->default(0);
            $table->integer('t5_p')->default(0);
            $table->integer('t6_l')->default(0);
            $table->integer('t6_p')->default(0);
            $table->integer('t7_l')->default(0);
            $table->integer('t7_p')->default(0);
            $table->integer('t8_l')->default(0);
            $table->integer('t8_p')->default(0);
            $table->integer('t9_l')->default(0);
            $table->integer('t9_p')->default(0);
            $table->integer('t10_l')->default(0);
            $table->integer('t10_p')->default(0);
            $table->integer('t11_l')->default(0);
            $table->integer('t11_p')->default(0);
            $table->integer('t12_l')->default(0);
            $table->integer('t12_p')->default(0);
            $table->integer('t13_l')->default(0);
            $table->integer('t13_p')->default(0);
            $table->integer('jumlah_ruang_kelas')->default(0);
            $table->integer('guru')->default(0);
            $table->integer('tendik')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dapodik_data_pokok');
    }
}; 