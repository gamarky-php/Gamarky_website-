<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'export_shipment_id',
        'line_name',
        'category',
        'col_index',
        'amount',
        'currency',
        'meta',
    ];

    protected $casts = [
        'amount' => 'float',
        'col_index' => 'integer',
        'meta' => 'array',
    ];

    public function shipment()
    {
        return $this->belongsTo(ExportShipment::class, 'export_shipment_id');
    }
}
