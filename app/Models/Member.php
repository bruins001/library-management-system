<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    /**
     * Mass assignable
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email'
    ];
}
