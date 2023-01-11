<?php

namespace App\Models;

use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    use HasFactory, Encryptable;

    protected $fillable = ['user_id','meta_key', 'meta_value'];

    protected $encryptable = [
        'meta_value',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
