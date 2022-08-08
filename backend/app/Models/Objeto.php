<?php

namespace App\Models;

use \Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
use \Staudenmeir\LaravelCte\Eloquent\QueriesExpressions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    public function objectable(): MorphTo
    {
        return $this->morphTo();
    }

    public static function tree()
    {
        $allObjects = Objeto::get();
        $rootObjects = $allObjects->whereNull('parent_id');
        self::formatTree($rootObjects, $allObjects);

        return $rootObjects;
    }

    private static function formatTree($rootObjects, $allobjetcts)
    {
        foreach ($rootObjects as $object) {
            $object->children = $allobjetcts->where('parent_id', $object->id)->values();
            if ($object->children->isNotEmpty()) {
                self::formatTree($object->children, $allobjetcts);
            }
        }
    }
}
