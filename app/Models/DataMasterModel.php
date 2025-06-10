<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\PenjualanModel;

class DataMasterModel extends Model
{
    protected $table = 'data_master';
    protected $primaryKey = 'id_master';
    public $timestamps = false;

    protected $fillable = [
        'platform',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function penjualan(): HasMany
    {
        return $this->hasMany(PenjualanModel::class, 'id_master', 'id_master');
    }
}
