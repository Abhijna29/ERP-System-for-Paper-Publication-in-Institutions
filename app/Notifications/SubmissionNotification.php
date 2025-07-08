<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SubmissionNotification extends Notification
{
    use Queueable;

    protected $item;
    protected $type;

    public function __construct($item, $type = 'paper')
    {
        $this->item = $item;
        $this->type = $type;
    }

    public function via(object $notifiable)
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable)
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
            'title' => ucfirst($this->type) . ' Submitted: ' . $title,
            'id' => $this->item->id,
            'message' => 'A new ' . str_replace('_', ' ', $this->type) . ' has been submitted by a researcher.',
            'role' => $notifiable->role ?? null,
            'paper_id' => $this->type === 'paper' ? $this->item->id : null,
            'chapter_id' => $this->type === 'book_chapter' ? $this->item->id : null,
            'link' => $link
        ];
    }
}
