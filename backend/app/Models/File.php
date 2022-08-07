<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Objeto;

class File extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'size',
        'path',
        'uuid',
        'author_id'
    ];

    public function sizeForHumans()
    {
        $bytes = $this->size;

        $units = ['b', 'kb', 'gb', 'tb'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . $units[$i];
    }

    public static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
        static::creating(function ($model) {
            $model->author_id = Auth::user()->id;
        });
        static::deleting(function ($model){
            Storage::disk('local')->delete($model->path);
        });
    }

    public function objects()
    {
        return $this->morphMany(Objeto::class, 'objectable');
    }
}
