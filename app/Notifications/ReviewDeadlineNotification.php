<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewDeadlineNotification extends Notification
{
    protected $review;
    protected $message;
    protected $type;

    public function __construct($review, string $type = 'paper')
    {

        $this->type = $type;
        $this->review = $review;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {

        $today = \Carbon\Carbon::today();
        $deadlineDate = \Carbon\Carbon::parse($this->review->deadline)->startOfDay(); // ensure time is midnight
        $daysLeft = (int)$today->diffInDays($deadlineDate, false);
        // dd([
        //     'today' => $today->toDateString(),
        //     'deadline' => $deadlineDate->toDateString(),
        //     'daysLeft' => $daysLeft,
        // ]);

        $dayText = match (true) {
            $daysLeft > 1 => "$daysLeft days left",
            $daysLeft === 1 => "1 day left",
            $daysLeft === 0 => "Deadline is today!",
            default => "Deadline passed",
        };

        $title = $this->type === 'chapter'
            ? $this->review->bookChapter->chapter_title
            : $this->review->researchPaper->title;

        return [
            'title' => 'Review Deadline Reminder',
            'message' => "Your review for \"{$title}\" is due on {$deadlineDate->format('d M Y')} ($dayText).",
            'role' => 'reviewer',
            'link' => route('reviewer.reviewForm', [
                'type' => $this->type,
                'id' => $this->type === 'chapter'
                    ? $this->review->bookChapter->id
                    : $this->review->researchPaper->id
            ]),
        ];
    }
}
