<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','slug', 'parent_id', 'is_visible', 'description'
    ];

    public function parent(): BelongsTo
    {
        //relacionamento consigo mesmo (parent)
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function child(): HasMany
    {
        //um filho pode ter varias categorias
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products(): BelongsToMany
    {
        //uma categoria pode ter varios produtos
        return $this->belongsToMany(Product::class);
    }
}
