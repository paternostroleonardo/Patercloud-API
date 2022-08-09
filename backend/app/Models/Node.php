<?php

namespace App\Models;

use \Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
use \Staudenmeir\LaravelCte\Eloquent\QueriesExpressions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Relations\MorphToManyOfDescendants;

class Node extends Model
{
    use HasFactory, SoftDeletes, HasRecursiveRelationships, QueriesExpressions;

    protected $fillable = [
        'uuid',
        'nodeable_id',
        'nodeable_type',
        'parent_id'
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

    public function folders()
    {
        return $this->morphedByMany(Folder::class, 'nodeable');
    }

    public function files()
    {
        return $this->morphedByMany(File::class, 'nodeable');
    }

    public function nodeable()
    {
        return $this->morphTo();
    }

    public static function inicializateTree()
    {
        $allObjects = self::get();
        $rootObjects = $allObjects->whereNull('parent_id');
        self::formatTree($rootObjects, $allObjects);

        return $rootObjects;
    }

    private static function formatTree($rootObjects, $allObjetcts)
    {
        foreach ($rootObjects as $object) {
            $object->children = $allObjetcts->where('parent_id', $object->id)->values();
            if ($object->children->isNotEmpty()) {
                self::formatTree($object->children, $allObjetcts);
            }
        }
    }
}
