<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;

class PaperStatusUpdated extends Notification
{
    use Queueable;

    protected $item;
    protected $action;
    protected $type;

    public function __construct($item, string $action, string $type = 'paper')
    {
        $this->item = $item;
        $this->action = $action;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $title = $this->type === 'paper' ? $this->item->title : $this->item->chapter_title;

        return [
            'type' => $this->type,
            'title' => ucfirst($this->type) . ' Status Update',
            'message' => "Your " . str_replace('_', ' ', $this->type) . " titled \"{$title}\" has been {$this->action}.",
            'id' => $this->item->id,
            'paper_title' => $title,
            'link' => $this->type === 'paper'
                ? route('papers.submitted', $this->item->id)
                : route('chapters.submitted'),
        ];
    }
}
