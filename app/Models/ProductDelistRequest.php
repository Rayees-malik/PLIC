<?php

namespace App\Models;

use App\Helpers\StatusHelper;
use App\RecordableModel;
use App\Traits\Orderable;
use App\Traits\RequiresSignoff;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductDelistRequest extends RecordableModel
{
    use RequiresSignoff;
    use Orderable;
    use HasFactory;

    protected $guarded = ['id'];

    protected $eager_relations = ['user', 'product'];

    public function onSignoffComplete($signoff)
    {
        Product::disableRecording();
        $this->product->status = StatusHelper::DISCONTINUED;
        $this->product->save();
        Product::enableRecording();
    }

    public function scopeSignoffFilter($query, $user = null)
    {
        $query->whereHas('product.brand', function ($query) use ($user) {
            $query->signoffFilter($user);
        });
    }

    public function scopeWithAccess($query, $user = null)
    {
        $query->whereHas('product.brand', function ($query) use ($user) {
            $query->withAccess($user);
        });
    }

    public function getDisplayNameAttribute()
    {
        return "{$this->name} [{$this->product->brand->name}]";
    }

    public function getRoutePrefixAttribute()
    {
        return 'productdelists';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'submitted_by', 'id')->withTrashed();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
