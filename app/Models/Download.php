<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'research_paper_id',
        'book_chapter_id',
        'downloaded_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function researchPaper()
    {
        return $this->belongsTo(ResearchPaper::class);
    }

    public function bookChapter()
    {
        return $this->belongsTo(BookChapter::class);
    }
}
