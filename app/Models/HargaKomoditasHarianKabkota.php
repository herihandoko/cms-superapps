<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HargaKomoditasHarianKabkota extends Model
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
    protected $table = 'harga_komoditas_harian_kabkota';

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
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_kab',
        'waktu',
        'id_komoditas',
        'harga',
        'source',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'waktu' => 'date',
        'harga' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the komoditas that owns the price.
     */
    public function komoditas(): BelongsTo
    {
        return $this->belongsTo(Komoditas::class, 'id_komoditas', 'id_kmd');
    }

    /**
     * Get the administrasi (kabupaten/kota) that owns the price.
     */
    public function administrasi(): BelongsTo
    {
        return $this->belongsTo(Administrasi::class, 'kode_kab', 'kd_adm');
    }
}