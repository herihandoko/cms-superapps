<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisdukcapilOpPenduduk extends Model
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
    protected $table = 'disdukcapil_op_penduduk';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true; // created_at and updated_at are present

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'waktu',
        'kd_adm3',
        'jlh_pddk',
        'tgt_wktpd',
        'rek_wktpd',
        'brek_wktpd',
        'prr',
        'kep_ktp_el',
        'aktv_ikd',
        'sisa_blktp',
        'tgt_kia',
        'mmlk_kia',
        'tgt_akt_lhr_0018',
        'mmlk_akt_lhr_0018',
        'source',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'waktu' => 'date',
        'kd_adm3' => 'integer',
        'jlh_pddk' => 'integer', // DDL says bigint, but integer cast is usually fine for Eloquent unless numbers are extremely large
        'tgt_wktpd' => 'integer',
        'rek_wktpd' => 'integer',
        'brek_wktpd' => 'integer',
        'prr' => 'integer',
        'kep_ktp_el' => 'integer',
        'aktv_ikd' => 'integer',
        'sisa_blktp' => 'integer',
        'tgt_kia' => 'integer',
        'mmlk_kia' => 'integer',
        'tgt_akt_lhr_0018' => 'integer',
        'mmlk_akt_lhr_0018' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the Master Administrasi associated with the DisdukcapilOpPenduduk.
     */
    public function masterAdministrasi(): BelongsTo
    {
        return $this->belongsTo(MasterAdministrasi::class, 'kd_adm3', 'kd_adm');
    }
}