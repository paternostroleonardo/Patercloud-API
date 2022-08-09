<?php

namespace App\Models;

use \Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Relations\MorphToManyOfDescendants;
use App\Models\Node;

class Folder extends Model
{
    use HasFactory, SoftDeletes, HasRecursiveRelationships;

    protected $fillable = [
        'name',
        'uuid',
        'author_id'
    ];

    public static function booted(){
        static::creating(function ($model){
            $model->uuid = Str::uuid();
        });
/*         static::creating(function ($model) {
            $model->author_id = Auth::user()->id;
        }); */
    }

    public function nodes(): MorphToMany
    {
        return $this->morphToMany(Node::class, 'nodeable');
    }

    public function recursiveNodes(): MorphToManyOfDescendants
    {
        return $this->morphToManyOfDescendants(Node::class, 'nodeable');
    }
}
