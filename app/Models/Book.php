<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['title', 'isbn', 'doi', 'edition', 'genre', 'publisher', 'publication_date'];

    public function chapters()
    {
        return $this->hasMany(BookChapter::class);
    }
}
