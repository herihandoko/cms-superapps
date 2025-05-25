<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Import SoftDeletes
use Illuminate\Database\Eloquent\Concerns\HasUuids; // Import HasUuids

class MasterOpd extends Model
{
    use HasFactory, SoftDeletes, HasUuids; // Add SoftDeletes and HasUuids

/**
     * The database connection that should be used by the model.
     *
     * @var string
     */
    protected $connection = 'superapps';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'master_opd';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'flag_cdn_source',
        'uuid',
        'kode_opd',
        'nama_opd',
        'nama_pimpinan',
        'nip_pimpinan',
        'jabatan_pimpinan',
        'golongan_pimpinan',
        'pangkat_pimpinan',
        'foto_pimpinan',
        'domain_opd',
        'alamat_opd',
        'email_opd',
        'no_kontak_opd',
        'berlaku_mulai',
        'berlaku_selesai',
        'flag_aktif',
        'jenis_opd',
        'icon_opd',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'flag_cdn_source' => 'integer',
        'berlaku_mulai' => 'date',
        'berlaku_selesai' => 'date',
        'flag_aktif' => 'integer', // Or 'boolean' if you prefer
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array<int, string>
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }
}
