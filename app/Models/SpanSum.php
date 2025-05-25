<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpanSum extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'superapps.span_sum';

    /**
     * Indicates if the model should be timestamped.
     * Laravel expects created_at and updated_at.
     * Your DDL has create_at and update_at.
     * We'll define these constants to match your DDL.
     *
     * @var bool
     */
    public $timestamps = true;

    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_opd',
        'belum_terverifikasi',
        'belum_ditindaklanjuti',
        'proses',
        'selesai',
        'total_aduan',
        'tindak_lanjut',
        'tgl_aduan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tgl_aduan' => 'date',
        'belum_terverifikasi' => 'integer',
        'belum_ditindaklanjuti' => 'integer',
        'proses' => 'integer',
        'selesai' => 'integer',
        'total_aduan' => 'integer',
        'tindak_lanjut' => 'integer',
        'create_at' => 'datetime',
        'update_at' => 'datetime',
    ];

    /**
     * Get the OPD that owns the SpanSum.
     */
    public function masterOpd(): BelongsTo
    {
        return $this->belongsTo(MasterOpd::class, 'kode_opd', 'id');
    }
}