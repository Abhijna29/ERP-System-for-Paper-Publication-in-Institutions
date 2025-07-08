<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReviewerAssignedNotification extends Notification
{
    use Queueable;

    protected $item;
    protected $type;

    /**
     * Create a new notification instance.
     */
    public function __construct($item, string $type = 'paper')
    {
        $this->item = $item;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable)
    {
        return ['database'];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable)
    {
        $title = $this->type === 'paper'
            ? $this->item->title
            : $this->item->chapter_title;

        return [
            'type' => $this->type,
            'title' => 'You Have Been Assigned a ' . ucfirst(str_replace('_', ' ', $this->type)) . ' for Review: ' . $title,
            'id' => $this->item->id,
            'message' => 'A ' . str_replace('_', ' ', $this->type) . ' has been assigned to you for review.',
            'link' => route('reviewer.reviewForm', [
                'type' => $this->type,
                'id' => $this->item->id
            ]),
        ];
    }
}
