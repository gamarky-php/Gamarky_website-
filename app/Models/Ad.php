<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use App\Models\Supplier;

class Ad extends Model
{
    protected $fillable = [
        'supplier_id', 'title', 'image_path', 'link_url', 'is_active', 'starts_at', 'ends_at', 'impressions', 'clicks', 'priority'
    ];

    protected $casts = [
        'is_active' => 'bool',
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
        'impressions' => 'integer',
        'clicks' => 'integer',
        'priority' => 'integer',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function scopeActiveForSpecialty($q, $specialty = null)
    {
        $now = Carbon::now();
        $q->where('is_active', true)
          ->where(function ($sub) use ($now) {
              $sub->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
          })
          ->where(function ($sub) use ($now) {
              $sub->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
          })
          ->whereHas('supplier', function ($s) use ($specialty) {
              if ($specialty) {
                  $s->where('specialty', $specialty);
              }
              $s->where('approved', true);
          });

        return $q;
    }
}
