<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order_activities extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id' ,
        'title',
    ];
}
