<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class External_ID extends Model
{
    use HasFactory;

    protected $fillable = [
        'kpID',
    ];
}
