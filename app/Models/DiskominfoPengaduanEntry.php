<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiskominfoPengaduanEntry extends Model
{
    use HasFactory;

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'superapps';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'diskominfo_op_span_lapor_data_entry';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'unit_kerja',
        'belum_terverifikasi',
        'belum_ditindaklanjuti',
        'proses',
        'selesai',
        'total',
        'persentase_tl',
        'rtl',
        'rhp'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'belum_terverifikasi' => 'integer',
        'belum_ditindaklanjuti' => 'integer',
        'proses' => 'integer',
        'selesai' => 'integer',
        'total' => 'integer',
        'persentase_tl' => 'decimal:2',
        'rtl' => 'integer',
        'rhp' => 'integer',
    ];
} 