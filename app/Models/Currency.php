<?php

namespace App\Models;

use App\Traits\Orderable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use SoftDeletes;
    use HasFactory;
    use Orderable;

    protected $fillable = ['name', 'exchange_rate'];

    public function brands()
    {
        return $this->hasMany(Vendor::class);
    }
}
