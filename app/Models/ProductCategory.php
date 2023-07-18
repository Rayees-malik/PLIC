<?php

namespace App\Models;

use App\Traits\HasRegulatoryFlags;
use App\Traits\Orderable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use Orderable;
    use HasRegulatoryFlags;
    use HasFactory;

    protected $guarded = ['id'];

    public function subcategories()
    {
        return $this->belongsToMany(ProductSubcategory::class);
    }
}
