<?php

// app/Models/Trademark.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trademarks extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'application_number',
        'status',
        'certificate_path',
        'application_date',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
