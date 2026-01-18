<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportShipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'origin_country',
        'pol',
        'pod',
        'incoterm',
        'method',
        'container_type',
        'weight_ton',
        'volume_cbm',
        'etd',
        'currency',
        'fx_rate',
        'status',
        'created_by',
    ];

    protected $casts = [
        'weight_ton' => 'float',
        'volume_cbm' => 'float',
        'fx_rate' => 'float',
        'etd' => 'date',
    ];

    public function costs()
    {
        return $this->hasMany(ExportCost::class);
    }

    public function quotes()
    {
        return $this->hasMany(ExportQuote::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
