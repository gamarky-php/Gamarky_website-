<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Supplier extends Model
{
    protected $table = 'suppliers';
    protected $fillable = [
        'company_name','province','city','contact_person','mr_ms',
        'mobile_phone','tel','fax','address','post_code','website',
        'introduction','main_products','company_name_cn','country_code',
        'status','source','external_id'
    ];

    /**
     * Scope to get only approved suppliers
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to filter suppliers based on request parameters
     */
    public function scopeFilter(Builder $query, $request): Builder
    {
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('company_name', 'like', '%' . $request->search . '%')
                  ->orWhere('main_products', 'like', '%' . $request->search . '%')
                  ->orWhere('introduction', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('country') && $request->country) {
            $query->where('country_code', $request->country);
        }

        if ($request->has('province') && $request->province) {
            $query->where('province', $request->province);
        }

        return $query;
    }
}
