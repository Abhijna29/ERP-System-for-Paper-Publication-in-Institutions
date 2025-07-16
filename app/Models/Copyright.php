<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Copyright extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'type_of_work',
        'registration_number',
        'registration_date',
        'certificate_path',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
