<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblChannel extends Model
{
    use HasFactory;

    protected $table = 'tbl_channels';
    protected $connection = 'mysql2';
}
