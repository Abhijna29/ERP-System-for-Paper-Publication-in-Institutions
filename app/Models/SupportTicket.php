<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $fillable = [
        'ticket_id',
        'issue_description',
        'submitted_by',
        'status',
        'acknowledgment',
        'guidance',
        'clarification',
        'user_reply',
    ];

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
}
