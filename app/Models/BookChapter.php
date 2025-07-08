<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookChapter extends Model
{
    protected $fillable = [
        'book_id',
        'user_id',
        'chapter_title',
        'abstract',
        'keywords',
        'genre',
        'comments',
        'chapter_publication_date',
        'file_path',
        'status',
        'chapter_doi',
        'resubmission_count',
        'page_number',
        'collaborations',
    ];

    protected $casts = [
        'collaborations' => 'array',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany(BookChapterReviews::class, 'book_chapter_id');
    }

    public function getAllAuthorsAttribute()
    {
        $authors = [$this->user->name]; // Main author

        // Add collaboration authors
        if (!empty($this->collaborations)) {
            // Foreign author
            if (!empty($this->collaborations['foreign']['author'])) {
                $authors[] = $this->collaborations['foreign']['author'];
            }
            // Indian author
            if (!empty($this->collaborations['indian']['author'])) {
                $authors[] = $this->collaborations['indian']['author'];
            }
            // Additional authors
            if (!empty($this->collaborations['additional']) && is_array($this->collaborations['additional'])) {
                foreach ($this->collaborations['additional'] as $additional) {
                    if (!empty($additional['author'])) {
                        $authors[] = $additional['author'];
                    }
                }
            }
        }

        // Remove duplicates and empty names
        $authors = array_filter(array_unique($authors), fn($name) => !empty($name));

        // Join with commas
        return implode(', ', $authors);
    }
}
