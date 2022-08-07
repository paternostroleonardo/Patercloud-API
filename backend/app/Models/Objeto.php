<?php

namespace App\Models;

use \Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
use \Staudenmeir\LaravelCte\Eloquent\QueriesExpressions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Folder;
use App\Models\File;
use App\Models\User;

class Objeto extends Model
{
    use HasFactory, SoftDeletes, HasRecursiveRelationships, QueriesExpressions;

    protected $table = 'objects';

    protected $fillable = [
        'uuid',
        'parent_id',
        'objectable_id',
        'objectable_type'
    ];

    public static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
        static::deleting(function ($model) {
            optional($model->objeto)->delete();
            $model->descendants->each->delete();
        });
    }

    public function objectable()
    {
        return $this->morphTo();
    }

    public static function tree()
    {
        $allObject = Objeto::get();
        $rootObjects = $allObject->whereNull('parent_id');
        self::formatTree($rootObjects, $allObject);

        return $rootObjects;
    }

    private static function formatTree($rootObjects, $allobjetos)
    {
        foreach ($rootObjects as $objeto) {
            $objeto->children = $allobjetos->where('parent_id', $objeto->id)->values();
            if ($objeto->children->isNotEmpty()) {
                self::formatTree($objeto->children, $allobjetos);
            }
        }
    }
}
