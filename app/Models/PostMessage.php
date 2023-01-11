<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'body',
        'post_at',
        'status'
    ];

    protected $dates = [
        'post_at',
    ];


    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
