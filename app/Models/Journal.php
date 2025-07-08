<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $fillable = ['name'];

    public function papers()
    {
        return $this->hasMany(ResearchPaper::class);
    }
}
