<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesignRight extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'design_class',
        'registration_date',
        'certificate_path',
        'design_file_path',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
