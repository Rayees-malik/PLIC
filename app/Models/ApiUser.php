<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class ApiUser extends Model implements AuthAuthenticatable
{
    use HasFactory, HasApiTokens;

    protected $guarded = [];

    public function getAuthIdentifierName()
    {
        return $this->name;
    }

    public function getAuthIdentifier()
    {
        return $this->id;
    }

    public function getAuthPassword()
    {
        return null;
    }

    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value)
    {
    }

    public function getRememberTokenName()
    {
        return null;
    }
}
