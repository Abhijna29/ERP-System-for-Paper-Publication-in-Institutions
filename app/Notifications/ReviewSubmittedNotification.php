<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReviewSubmittedNotification extends Notification
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
        $type = $this->type;
        $id = $this->item->id;

        if ($notifiable->role === 'institution') {
            $link = route('institution.submissions.index', ['type' => $type]);
        } elseif ($notifiable->role === 'department') {
            $link = route('department.submissions.index', ['type' => $type]);
        } elseif ($notifiable->role === 'admin') {
            if ($type === 'paper') {
                $link = route('admin.papers');
            } else {
                $link = route('admin.bookChapters.index');
            }
        } elseif ($notifiable->role === 'researcher') {
            if ($type === 'paper') {
                $link = route('papers.submitted');
            } else {
                $link = route('chapters.submitted');
            }
        } else {
            $link = '#';
        }
        return [
            'type' => $this->type,
            'title' => 'Review Submitted for Your ' . ucfirst(str_replace('_', ' ', $this->type)) . ': ' . $title,
            'id' => $this->item->id,
            'message' => 'A reviewer has submitted their review for your ' . str_replace('_', ' ', $this->type) . '.',
            'link' => $link,
        ];
    }
}
