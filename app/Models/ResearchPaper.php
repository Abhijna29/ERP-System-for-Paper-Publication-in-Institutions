<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResearchPaper extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $fillable = [
        'title',
        'abstract',
        'keywords',
        'file_path',
        'user_id',
        'status',
        'category_id',
        'sub_category_id',
        'child_category_id',
        'resubmission_count',
        'source',
        'volume_number',
        'issue_number',
        'page_number',
        'publication_date',
        'doi',
        'percentile',
        'journal_category',
        'collaborations',
        'indexing_database'

    ];

    protected $casts = [
        'collaborations' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function childCategory()
    {
        return $this->belongsTo(ChildCategory::class, 'child_category_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'research_paper_id');
    }

    public function reviewers()
    {
        return $this->belongsToMany(User::class, 'reviews', 'research_paper_id', 'reviewer_id')
            ->withPivot('comments', 'rating', 'status', 'flagged_for_editor');
    }

    // In app/Models/ResearchPaper.php

    public function researcher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department()
    {
        return $this->belongsTo(User::class, 'department_id');
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

    public function downloads()
    {
        return $this->hasMany(Download::class);
    }
}
