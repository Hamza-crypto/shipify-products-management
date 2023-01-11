<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bin extends Model
{
    use HasFactory;

    protected $fillable = ['number', 'min_amount', 'max_amount', 'gateway_id'];

    public function gateway()
    {
        return $this->belongsTo(Gateway::class);
    }
}
