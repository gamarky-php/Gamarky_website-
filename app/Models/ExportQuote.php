<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportQuote extends Model
{
    use HasFactory;

    protected $fillable = [
        'export_shipment_id',
        'quote_no',
        'incoterm_final',
        'total_cost',
        'unit_cost',
        'margin_pct',
        'sell_price',
        'currency',
        'pdf_path',
        'status',
    ];

    protected $casts = [
        'total_cost' => 'float',
        'unit_cost' => 'float',
        'margin_pct' => 'float',
        'sell_price' => 'float',
    ];

    public function shipment()
    {
        return $this->belongsTo(ExportShipment::class, 'export_shipment_id');
    }
}
