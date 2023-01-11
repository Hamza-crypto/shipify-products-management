<?php

namespace App\Models;

use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    use HasFactory, Encryptable;

    protected $fillable = ['title', 'api_key', 'api_secret'];

    protected $encryptable = ['api_key', 'api_secret'];

    public function bins()
    {
        return $this->belongsToMany(BIN::class);
    }

}
