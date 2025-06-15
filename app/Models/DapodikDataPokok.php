<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DapodikDataPokok extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The database connection that should be used by the model.
     *
     * @var string
     */
    protected $connection = 'superapps';

    protected $table = 'dapodik_data_pokok';

    protected $fillable = [
        'nama_satuan_pendidikan',
        'npsn',
        'bentuk_pendidikan',
        'status_sekolah',
        'alamat',
        'desa',
        'kecamatan',
        'kabupaten_kota',
        'lintang',
        'bujur',
        'tmt_akreditasi',
        'akreditasi',
        'rombel_t1',
        'rombel_t2',
        'rombel_t3',
        'rombel_t4',
        'rombel_t5',
        'rombel_t6',
        'rombel_t7',
        'rombel_t8',
        'rombel_t9',
        'rombel_t10',
        'rombel_t11',
        'rombel_t12',
        'rombel_t13',
        'rombel_tka',
        'rombel_tkb',
        'rombel_pkta',
        'rombel_pktb',
        'rombel_pktc',
        'jumlah_rombel',
        'peserta_didik_baru',
        'tka_l',
        'tka_p',
        'tkb_l',
        'tkb_p',
        't1_l',
        't1_p',
        't2_l',
        't2_p',
        't3_l',
        't3_p',
        't4_l',
        't4_p',
        't5_l',
        't5_p',
        't6_l',
        't6_p',
        't7_l',
        't7_p',
        't8_l',
        't8_p',
        't9_l',
        't9_p',
        't10_l',
        't10_p',
        't11_l',
        't11_p',
        't12_l',
        't12_p',
        't13_l',
        't13_p',
        'jumlah_ruang_kelas',
        'guru',
        'tendik',
    ];

    protected $casts = [
        'tmt_akreditasi' => 'date',
    ];
} 