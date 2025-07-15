<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patents extends Model
{
    protected $fillable = [
        'user_id',
        'investors_name',
        'work_title',
        'work_description',
        'year',
        'type',
        'publication_number',
        'grant_number',
        'certificate_path',
        'research_paper_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
