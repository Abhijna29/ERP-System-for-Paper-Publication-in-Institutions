<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Review;
use App\Models\BookChapterReviews;
use App\Notifications\ReviewDeadlineNotification;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::call(function () {
    $today = now()->startOfDay();
    $upcoming = $today->copy()->addDays(3);
    $yesterday = $today->copy()->subDay();

    // ✅ Upcoming deadlines (today to +3 days)
    Review::whereDate('deadline', '>=', $today)
        ->whereDate('deadline', '<=', $upcoming)
        ->where('status', 'pending')
        ->with(['reviewer', 'researchPaper'])
        ->get()
        ->each(function ($review) use ($today) {
            if ($review->reviewer && (!$review->last_notified_at || !$review->last_notified_at->isSameDay($today))) {
                $review->reviewer->notify(new ReviewDeadlineNotification($review, 'paper'));
                $review->update(['last_notified_at' => $today]);
            }
        });

    // ✅ Missed yesterday
    Review::whereDate('deadline', '=', $yesterday)
        ->where('status', 'pending')
        ->with(['reviewer', 'researchPaper'])
        ->get()
        ->each(function ($review) use ($today) {
            if ($review->reviewer && (!$review->last_notified_at || !$review->last_notified_at->isSameDay($today))) {
                $review->reviewer->notify(new ReviewDeadlineNotification($review, 'paper'));
                $review->update(['last_notified_at' => $today]);
            }
        });

    // Repeat similarly for BookChapterReviews if needed
})->everyMinute(); // switch back to daily() in production
