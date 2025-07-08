<?php

namespace App\Notifications;

use App\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminTicketReplyNotification extends Notification
{
    use Queueable;
    protected $ticket;

    /**
     * Create a new notification instance.
     */
    public function __construct(SupportTicket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the databse representation of the notification.
     */
    public function toDatabase(object $notifiable)
    {
        return ([
            'title' => 'Support Ticket Replied',
            'message' => 'Admin has replied to your support ticket: ' . $this->ticket->ticket_id,
            'ticket_id' => $this->ticket->ticket_id,
            'link' => route('supportTickets.index'),
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
