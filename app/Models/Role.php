<?php

namespace App\Models;

use App\Traits\Orderable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Silber\Bouncer\Database\Role as BaseRole;

class Role extends BaseRole
{
    use SoftDeletes;
    use Orderable;

    protected const ORDER_BY = ['category' => 'asc', 'title' => 'asc'];

    protected $fillable = ['name', 'title', 'level', 'description', 'category'];

    public function scopeEditableByUser($query, $allowOwn = false)
    {
        $user = auth()->user();
        if ($user->isVendor) {
            return $query->whereRaw('1 = 2');
        }

        $filterAbilities = $user->can('admin.edit') ? function ($query) {
        } : function ($query) {
            $query->where('category', '<>', 'Admin');
        };
        $query->whereHas('abilities', $filterAbilities);

        if ($allowOwn) {
            $query->orWhereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            });
        }
    }
}
