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

    // Notify paper reviewers with deadline between today and next 3 days
    Review::whereDate('deadline', '>=', $today)
        ->whereDate('deadline', '<=', $upcoming)
        ->where('status', 'pending')
        ->with('reviewer', 'researchPaper')
        ->get()
        ->each(function ($review) {
            if ($review->reviewer) {
                $review->reviewer->notify(new ReviewDeadlineNotification($review, 'paper'));
            }
        });

    // Notify chapter reviewers with deadline between today and next 3 days
    BookChapterReviews::whereDate('deadline', '>=', $today)
        ->whereDate('deadline', '<=', $upcoming)
        ->where('status', 'pending')
        ->with('reviewer', 'bookChapter')
        ->get()
        ->each(function ($review) {
            if ($review->reviewer) {
                $review->reviewer->notify(new ReviewDeadlineNotification($review, 'chapter'));
            }
        });
})->daily(); // For testing; use daily() in production
