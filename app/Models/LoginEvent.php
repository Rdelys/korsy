<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginEvent extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['user_id', 'ip', 'user_agent'];
}