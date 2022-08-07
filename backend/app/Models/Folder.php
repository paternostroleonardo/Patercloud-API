<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Folder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'uuid',
        'author_id'
    ];

    public static function booted(){
        static::creating(function ($model){
            $model->uuid = Str::uuid();
        });
        static::creating(function ($model) {
            $model->author_id = Auth::user()->id;
        });
    }

    public function objects()
    {
        return $this->morphMany(Objeto::class, 'objectable');
    }
}
