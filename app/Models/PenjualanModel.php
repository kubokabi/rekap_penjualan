<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenjualanModel extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'id_penjualan';
    public $timestamps = false;

    protected $fillable = [
        'id_master',
        'nama_kolom',
        'isi_kolom',
    ];

    public function master(): BelongsTo
    {
        return $this->belongsTo(DataMasterModel::class, 'id_master', 'id_master');
    }
}
