<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'product_id',
        'sku',
        'upc',
        'num_of_images',
        'status',
        'is_blacklisted',
        'store_id',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function blacklist_status()
    {
        return $this->is_blacklisted == 1 ? 'Blacklisted' : 'OK';
    }

    public function getStatusColor()
    {
        if ($this->is_blacklisted == 1) return 'danger';
        if ($this->is_blacklisted == 0) return 'success';
    }

    public function scopeFilters($query, $request)
    {
        if (isset($request['store'])) {
            $query->where('store_id', $request['store']);
        }

        if (isset($request['status'])) {
            $query->where('is_blacklisted', $request['status']);
        }

        $query->orderBy('created_at', 'desc');
    }
}
