<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KetersediaanPangan extends Model
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
    protected $table = 'ketersediaan_pangan';

    /**
     * Indicates if the model's ID is auto-incrementing.
     * The DDL shows id as nullable, but we'll assume it's the primary key.
     * If it's not auto-incrementing, set this to false.
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
    public $timestamps = true; // DDL has created_at and updated_at

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_pangan',
        'proyeksi',
        'neraca',
        'output',
        'ketersediaan',
        'periode',
        'source',
        // 'id' is typically not fillable if auto-incrementing
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'proyeksi' => 'integer',
        'neraca' => 'integer',
        'output' => 'integer',
        'periode' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}