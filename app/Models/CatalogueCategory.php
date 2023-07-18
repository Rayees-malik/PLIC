<?php

namespace App\Models;

use App\Traits\Orderable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CatalogueCategory extends Model
{
    use SoftDeletes;
    use HasFactory;
    use Orderable;

    const ORDER_BY = ['sort' => 'asc', 'name' => 'asc'];

    protected $guarded = ['id'];

    public function getNameFr()
    {
        return $this->name_fr ?: $this->name;
    }

    public function scopeByBrand($query, $brand)
    {
        $id = $brand->id ?? $brand;
        $query->where('brand_id', $id);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
