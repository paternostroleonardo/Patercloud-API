<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nodeable extends Model
{
    use HasFactory;

    protected $fillable = [
        'node_id',
        'nodeable_id',
        'nodeable_type'
    ];
}
