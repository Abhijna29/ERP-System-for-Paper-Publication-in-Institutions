<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookChapterReviews extends Model
{
    protected $fillable = [
        'book_chapter_id',
        'reviewer_id',
        'comments',
        'status',
        'rating',
        'deadline',
        'flagged_for_editor',
    ];

    public function bookChapter()
    {
        return $this->belongsTo(BookChapter::class, 'book_chapter_id');
    }
    public function reviews()
    {
        return $this->hasMany(BookChapterReviews::class, 'book_chapter_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
