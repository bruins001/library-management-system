<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /**
     * Mass assignable
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'author',
        'ísbn',
        'status'
    ];
}
