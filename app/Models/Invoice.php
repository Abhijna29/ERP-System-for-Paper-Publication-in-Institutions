<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'research_paper_id',
        'book_chapter_id',
        'invoice_number',
        'amount',
        'status',
        'description',
        'invoice_date',
        'due_date',
    ];

    protected $dates = [
        'invoice_date',
        'due_date',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
