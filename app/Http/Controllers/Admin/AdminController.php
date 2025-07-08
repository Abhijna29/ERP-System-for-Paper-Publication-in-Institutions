<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResearchPaper;
use App\Models\User;
use App\Models\Review;
use App\Notifications\PaperStatusUpdated;
use App\Notifications\ReviewDeadlineNotification;
use App\Notifications\ReviewerAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    

    public function listOfPaper()
    {
        $papers = ResearchPaper::where('status', 'published')->with('user', 'category')->oldest()->get();

        return view('dashboard.admin.listOfPaper', compact('papers'));
    }
    public function generateReport()
    {
        return view('dashboard.admin.generateReport');
    }

    public function patentFiled()
    {
        return view('dashboard.admin.patentFiled');
    }
    public function patentPublished()
    {
        return view('dashboard.admin.patentPublished');
    }
    public function patentGranted()
    {
        return view('dashboard.admin.patentGranted');
    }
    public function copyrightFiled()
    {
        return view('dashboard.admin.copyrightFiled');
    }
    public function copyrightPublished()
    {
        return view('dashboard.admin.copyrightPublished');
    }
    public function copyrightGranted()
    {
        return view('dashboard.admin.copyrightGranted');
    }
    public function tradeMarkFiled()
    {
        return view('dashboard.admin.tradeMarkFiled');
    }
    public function tradeMarkPublished()
    {
        return view('dashboard.admin.tradeMarkPublished');
    }
    public function tradeMarkGranted()
    {
        return view('dashboard.admin.tradeMarkGranted');
    }
    public function designFiled()
    {
        return view('dashboard.admin.designFiled');
    }
    public function designPublished()
    {
        return view('dashboard.admin.designPublished');
    }
    public function designGranted()
    {
        return view('dashboard.admin.designGranted');
    }

    // Assign reviewers
    public function index()
    {
        $today = Carbon::today();

        $reviews = Review::with(['reviewer', 'researchPaper'])
            ->whereNotNull('deadline')
            ->where('status', 'pending')
            ->whereDate('deadline', '>=', $today)
            ->whereDate('deadline', '<=', $today->copy()->addDays(3))
            ->get();

        foreach ($reviews as $review) {
            $lastNotified = $review->last_notified_at ? Carbon::parse($review->last_notified_at) : null;

            if (!$lastNotified || !$lastNotified->isSameDay($today)) {
                $review->reviewer->notify(new ReviewDeadlineNotification($review, 'paper'));
                $review->update(['last_notified_at' => $today]);
            }
        }

        // Your dashboard logic
        $papers = ResearchPaper::with('user')->get();
        return view('dashboard.admin.papers', compact('papers'));
    }

    public function showAssignForm($id)
    {
        $paper = ResearchPaper::with('reviews')->orderBy('created_at', 'desc')->findOrFail($id);

        $researcherInstitutionId = $paper->user->institution_id;
        $researcherDepartmentId = $paper->user->department_id;
        $reviewers = User::where('role', 'reviewer')
            ->where('institution_id', $researcherInstitutionId)
            ->where('department_id', $researcherDepartmentId)
            ->withCount([
                'reviews as active_paper_reviews_count' => function ($query) {
                    $query->whereHas('researchPaper', function ($q) {
                        $q->where('status', '!=', 'published');
                    });
                },
                'bookChapterReviews as active_chapter_reviews_count' => function ($query) {
                    $query->whereHas('bookChapter', function ($q) {
                        $q->where('status', '!=', 'published');
                    });
                }
            ])
            ->get()
            ->map(function ($reviewer) {
                $reviewer->total_active_reviews =
                    $reviewer->active_paper_reviews_count + $reviewer->active_chapter_reviews_count;
                return $reviewer;
            })
            ->sortBy('total_active_reviews')
            ->values(); // reindex after sorting

        $assignedReviewers = $paper->reviews->pluck('reviewer_id')->toArray();
        // $journals = Journal::all();

        return view('dashboard.admin.assignReviewer', [
            'submission' => $paper,
            'reviewers' => $reviewers,
            'assignedReviewers' => $assignedReviewers,
            'type' => 'paper',
        ]);
    }

    public function assignReviewers(Request $request, $id)
    {
        $request->validate([
            'reviewers' => 'array|max:3',
            'reviewers.*' => 'exists:users,id',
            'deadlines' => 'array',
            // 'deadlines.*' => 'nullable|date|after:today'
            'deadlines.*' => 'nullable|date|after_or_equal:today'
        ]);

        $paper = ResearchPaper::findOrFail($id);
        $selectedReviewerIds = $request->input('reviewers', []);
        $deadlines = $request->input('deadlines', []);

        // Delete removed reviewers
        Review::where('research_paper_id', $id)
            ->whereNotIn('reviewer_id', $selectedReviewerIds)
            ->delete();

        $newAssignments = [];

        foreach ($selectedReviewerIds as $reviewerId) {
            $deadline = $deadlines[$reviewerId] ?? now()->addDays(7); // Default 7 days

            $review = Review::firstOrCreate(
                [
                    'research_paper_id' => $id,
                    'reviewer_id' => $reviewerId,
                ]
            );

            $review->deadline = $deadline;
            $review->status = 'pending';
            $review->save();

            if (!$review->wasRecentlyCreated) {
                // Already existed, but we updated deadline
            } else {
                $newAssignments[] = $reviewerId;
            }
        }

        // Notify newly assigned reviewers
        foreach ($newAssignments as $reviewerId) {
            $reviewer = User::find($reviewerId);
            $reviewer->notify(new ReviewerAssignedNotification($paper, 'paper'));
        }

        // Update paper status
        $remainingReviews = Review::where('research_paper_id', $id)->get();

        if ($remainingReviews->isEmpty()) {
            $paper->status = 'submitted';
        } elseif ($remainingReviews->contains('status', 'rejected')) {
            $paper->status = 'rejected';
        } elseif ($remainingReviews->every(fn($r) => $r->status === 'approved')) {
            $paper->status = 'approved';
        } else {
            $paper->status = 'under_review';
        }

        $paper->save();

        return redirect()->route('admin.papers')->with('success', 'Reviewers assigned with deadlines successfully.');
    }

    public function resolveFlag(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $paper = $review->researchPaper;
        $action = $request->input('action');
        $researcher = $paper->user;

        if ($action === 'resolve') {
            $review->flagged_for_editor = false;
            $paper->status = 'approved';
            $review->save();
            return back()->with('success', 'Flag marked as resolved.');
        } elseif ($action === 'request_revision') {
            $review->flagged_for_editor = false;

            $paper->status = 'revision_required';
            $paper->save();
            $review->save();
            //notify the researcher when the paper is updated by admin(resolve,reject, revision required)
            $researcher->notify(new PaperStatusUpdated($paper, 'requested for revision', 'paper'));

            return back()->with('success', 'Revision requested from researcher.');
        } elseif ($action === 'reject') {
            $review->flagged_for_editor = false;

            $paper->status = 'rejected';
            $paper->save();
            $review->save();
            return back()->with('success', 'Paper rejected based on editorial decision.');
        }

        return back()->with('error', 'Invalid action.');
    }

    public function showPaper($id)
    {
        $paper = ResearchPaper::with(['user', 'reviews.reviewer'])->findOrFail($id);
        return view('dashboard.admin.viewPaper', compact('paper'));
    }
}
