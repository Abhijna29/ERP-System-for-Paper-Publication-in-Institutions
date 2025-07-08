<?php

// app/Models/Review.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'research_paper_id',
        'reviewer_id',
        'comments',
        'rating',
        'status',
        'flagged_for_editor',
    ];

    // Define relationship with the ResearchPaper model
    public function researchPaper()
    {
        return $this->belongsTo(ResearchPaper::class, 'research_paper_id');
    }

    // Define relationship with the User model (for reviewer)
    public function reviewer()
    {
        return $this->belongsTo(User::class,  'reviewer_id');
    }
}
