<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restrict extends Model
{
    use HasFactory;

    protected $fillable = [
        'restriction',
    ];

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function restrict()
    {
        return $this->belongsTo(Restrict::class, 'restrict_id');
    }
}
