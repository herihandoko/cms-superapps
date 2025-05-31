<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiskominfoOpSpanLaporV2 extends Model
{
    protected $table = 'diskominfo_op_span_lapor_v2';
    protected $connection = 'superapps';

    protected $fillable = [
        'tracking_id',
        'tanggal_laporan',
        'waktu_laporan',
        'nama_pelapor',
        'klasifikasi_laporan',
        'id_kategori',
        'kategori',
        'judul_laporan',
        'isi_laporan_awal',
        'isi_laporan_akhir',
        'tipe_laporan',
        'sumber_laporan',
        'instansi_induk',
        'id_instansi_terdisposisi',
        'instansi_terdisposisi',
        'status_laporan',
        'alasan_tunda_arsip',
        'kd_provinsi',
        'kd_kabupaten_kota',
        'kd_kecamatan',
        'kd_desa_kelurahan',
        'source',
    ];

    protected $casts = [
        'tanggal_laporan' => 'date',
        'waktu_laporan' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
} 