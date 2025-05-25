<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komoditas extends Model
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
    protected $table = 'badanpangan_m_komoditas';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_kmd';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false; // Assuming id_kmd is not auto-incrementing based on DDL.
                                  // If it is, set to true and remove id_kmd from fillable.

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * The name of the "created at" column.
     *
     * @var string|null
     */
    const CREATED_AT = 'created_at';

    /**
     * The name of the "updated at" column.
     *
     * @var string|null
     */
    const UPDATED_AT = 'update_at'; // Matching DDL 'update_at'

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_kmd', // Primary key, assuming it needs to be manually set
        'nm_kmd', // Changed from nama_pangan
        'hpp_het', // Using this for the attribute, mapped to 'hpp/het' column via accessor/mutator
        'source',
        // 'satuan', // Removed as it's not in the new DDL
    ];

    // Removed custom accessor and mutator for hpp_het as the database column is hpp_het (underscore)
    // Eloquent will handle this automatically.
}