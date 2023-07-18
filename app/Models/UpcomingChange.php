<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpcomingChange extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'change_date', 'expires_at', 'scheduled_at',
    ];
}
