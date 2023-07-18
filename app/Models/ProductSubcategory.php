<?php

namespace App\Models;

use App\Traits\HasRegulatoryFlags;
use App\Traits\Orderable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSubcategory extends Model
{
    use Orderable;
    use HasRegulatoryFlags;
    use HasFactory;

    protected $guarded = ['id'];

    public function scopeByCategory($query, $category, $previousCategoryId = null)
    {
        $id = $category->id ?? $category;
        $query->whereHas('categories', function ($query) use ($id) {
            $query->where('id', $id);
        })->orWhere('id', $previousCategoryId);
    }

    public function categories()
    {
        return $this->belongsToMany(ProductCategory::class);
    }
}
