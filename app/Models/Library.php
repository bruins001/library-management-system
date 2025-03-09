<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Library extends Model
{
    /**
     * Mass assignable
     *
     * @var list<string>
     */
    protected $fillable = [
        'book_id',
        'member_id'
    ];

    public function book(): BelongsToMany
    {
        // I know this should be a one to one relationship, but laravel won't recognize that it should look in the libraries table and I am running out of time.
        return $this->belongsToMany(Book::class, 'libraries', 'book_id');
    }

    public function member(): BelongsToMany
    {
        // I know this should be a one to one relationship, but laravel won't recognize that it should look in the libraries table and I am running out of time.
        return $this->belongsToMany(Member::class, 'libraries', 'member_id');
    }
}
