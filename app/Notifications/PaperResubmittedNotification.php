<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaperResubmittedNotification extends Notification
{
    use Queueable;

    protected $item;
    protected $type;

    public function __construct($item, string $type = 'paper')
    {
        $this->item = $item;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $title = $this->type === 'paper'
            ? $this->item->title
            : $this->item->chapter_title;

        return [
            'type' => $this->type,
            'id' => $this->item->id,
            'title' => $title,
            'message' => 'The ' . str_replace('_', ' ', $this->type) . ' "' . $title . '" has been resubmitted by the researcher.',
            'link' => route('reviewer.reviewForm', [
                'type' => $this->type,
                'id' => $this->item->id

            ]),
        ];
    }
}
