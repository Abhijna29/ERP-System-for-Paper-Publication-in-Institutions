<?php

namespace App\Notifications;

use App\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Support\Facades\Log;

class UserTicketReplyNotification extends Notification
{
    use Queueable;

    protected $ticket;
    protected $title;

    public function __construct(SupportTicket $ticket, $title = 'User Replied to Support Ticket')
    {
        $this->ticket = $ticket;
        $this->title = $title;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => 'User ' . $this->ticket->submitter->name .
                ($this->title === 'New Support Ticket Submitted' ? ' submitted a new support ticket: ' : ' replied to ticket: ') .
                $this->ticket->ticket_id,
            'ticket_id' => $this->ticket->ticket_id,
            'link' => route('admin.supportTickets'),
        ];
    }
}
