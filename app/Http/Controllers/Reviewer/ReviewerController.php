<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\BookChapterReviews;
use App\Models\Review;
use App\Models\User;
use App\Notifications\ReviewSubmittedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ReviewerController extends Controller
{
    public function index(Request $request, $type)
    {
        // $type = $request->get('type', 'paper');

        if ($type === 'chapter') {
            $reviews = BookChapterReviews::with('bookChapter')
                ->where('reviewer_id', Auth::id())
                ->get();
            return view('dashboard.reviewer.chapter', compact('reviews'));
        }

        // Default: research papers
        $reviews = Review::with('researchPaper')
            ->where('reviewer_id', Auth::id())
            ->get();

        return view('dashboard.reviewer.papers', compact('reviews'));
    }


    public function showReviewForm($type, $id, Request $request)
    {
        // $type = $request->get('type', 'paper');

        if ($type === 'chapter') {
            $review = BookChapterReviews::where('reviewer_id', Auth::id())
                ->where('book_chapter_id', $id)
                ->firstOrFail();

            // return view('dashboard.reviewer.chapterReviewForm', compact('review'));
        } else {
            $review = Review::where('reviewer_id', Auth::id())
                ->where('research_paper_id', $id)
                ->firstOrFail();
        }
        return view('dashboard.reviewer.reviewerForm', compact('review', 'type'));
    }


    public function submitReview(Request $request, $type, $id)
    {
        $request->validate([
            'comments' => 'required|string',
            'rating' => 'nullable|numeric|min:1|max:5',
            'status' => 'required|in:pending,approved,rejected,revision_required,resubmitted',
        ]);

        if ($type === 'chapter') {
            $review = BookChapterReviews::where('reviewer_id', Auth::id())
                ->where('book_chapter_id', $id)
                ->firstOrFail();

            $today = now()->startOfDay();
            $deadlineDate = \Carbon\Carbon::parse($review->deadline)->startOfDay();

            if ($today->gt($deadlineDate)) {
                return redirect()->route('reviewer.reviews', ['type' => $type])
                    ->with('error', 'The deadline has passed. You can no longer submit this review.');
            }

            // Find the review and update it
            $review = BookChapterReviews::where('reviewer_id', Auth::id())
                ->where('book_chapter_id', $id)
                ->firstOrFail();

            $review->fill($request->only(['comments', 'rating', 'status']));
            $review->flagged_for_editor = $request->has('flagged_for_editor');
            $review->save();

            // Update chapter status based on all reviews
            $chapter = $review->bookChapter; // Make sure the relationship is defined and loaded
            $allReviews = BookChapterReviews::where('book_chapter_id', $id)->get();

            if ($allReviews->contains('status', 'rejected')) {
                $chapter->status = 'rejected';
            } elseif ($allReviews->every(fn($r) => $r->status === 'approved')) {
                $chapter->status = 'approved';
            } elseif ($allReviews->contains('status', 'revision_required')) {
                $chapter->status = 'revision_required';
            } else {
                $chapter->status = 'under_review';
            }

            $chapter->save();

            // Notify relevant users
            $researcher = User::find($chapter->user_id);
            if ($researcher) {
                $researcher->notify(new ReviewSubmittedNotification($chapter, 'chapter'));
            }
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $admin->notify(new ReviewSubmittedNotification($chapter, 'chapter'));
            }

            $institutionUser = User::where('role', 'institution')
                ->where('id', $researcher->institution_id)
                ->first();

            $departmentUser = User::where('role', 'department')
                ->where('id', $researcher->department_id)
                ->first();

            if ($institutionUser) {
                $institutionUser->notify(new ReviewSubmittedNotification($chapter, 'chapter'));
            }

            if ($departmentUser) {
                $departmentUser->notify(new ReviewSubmittedNotification($chapter, 'chapter'));
            }

            return redirect()->route('reviewer.reviews', ['type' => 'chapter'])
                ->with('success', 'Chapter review submitted successfully.');
        } else {
            //papers
            $review = Review::where('reviewer_id', Auth::id())
                ->where('research_paper_id', $id)
                ->firstOrFail();
            $today = now()->startOfDay();
            $deadlineDate = \Carbon\Carbon::parse($review->deadline)->startOfDay();

            if ($today->gt($deadlineDate)) {
                return redirect()->route('reviewer.reviews', ['type' => $type])
                    ->with('error', 'The deadline has passed. You can no longer submit this review.');
            }


            $review->fill($request->only(['comments', 'rating', 'status']));
            $review->flagged_for_editor = $request->has('flagged_for_editor');
            $review->save();

            $paper = $review->researchPaper;
            $allReviews = Review::where('research_paper_id', $id)->get();

            if ($allReviews->contains('status', 'rejected')) {
                $paper->status = 'rejected';
            } elseif ($allReviews->every(fn($r) => $r->status === 'approved')) {
                $paper->status = 'approved';
            } elseif ($allReviews->contains('status', 'revision_required')) {
                $paper->status = 'revision_required';
            } else {
                $paper->status = 'under_review';
            }

            $paper->save();

            // Notify relevant users
            $researcher = User::find($paper->user_id);
            if ($researcher) {
                $researcher->notify(new ReviewSubmittedNotification($paper, 'paper'));
            }
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $admin->notify(new ReviewSubmittedNotification($paper, 'paper'));
            }

            $institutionUser = User::where('role', 'institution')
                ->where('id', $researcher->institution_id)
                ->first();

            $departmentUser = User::where('role', 'department')
                ->where('id', $researcher->department_id)
                ->first();

            if ($institutionUser) {
                $institutionUser->notify(new ReviewSubmittedNotification($paper, 'paper'));
            }

            if ($departmentUser) {
                $departmentUser->notify(new ReviewSubmittedNotification($paper, 'paper'));
            }

            return redirect()->route('reviewer.reviews', ['type' => 'paper'])
                ->with('success', 'Paper review submitted successfully.');
        }
    }
}
