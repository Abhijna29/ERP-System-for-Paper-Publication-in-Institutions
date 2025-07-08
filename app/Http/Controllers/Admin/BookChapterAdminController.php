<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookChapter;
use App\Models\Book;
use App\Models\BookChapterReviews;
use App\Models\Review;
use App\Models\User;
use App\Notifications\PaperStatusUpdated;
use App\Notifications\ReviewDeadlineNotification;
use App\Notifications\ReviewerAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BookChapterAdminController extends Controller
{
    public function createBook()
    {
        $books = Book::all();
        return view('dashboard.admin.book-chapters.createBook', compact('books'));
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'isbn' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^(\d{10}|\d{13}|[\d\-]{10,20})$/'
            ],
            'doi' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^10\.\d{4,9}\/[-._;()\/:a-z0-9]+$/i'
            ],
            'edition' => 'required|integer|min:1',
            'genre' => 'nullable|string|max:255',
            'publisher' => 'required|string|max:255',
            'publication_date' => 'required|date',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = [
            'title' => $request->title,
            'isbn' => $request->isbn,
            'doi' => $request->doi,
            'edition' => $request->edition,
            'genre' => $request->genre,
            'publisher' => $request->publisher,
            'publication_date' => $request->publication_date,
        ];

        $book->update($data);

        return response()->json(['message' => 'Book updated successfully']);
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return response()->json(['message' => 'Book deleted successfully']);
    }

    public function getBooks()
    {
        $books = Book::select('id', 'title', 'isbn', 'doi', 'edition', 'genre', 'publisher', 'publication_date')
            ->get();
        return response()->json($books);
    }

    // public function submittedChapters()
    // {
    //     $chapters = BookChapter::where('status', '!=', 'published')
    //         ->with('user')->get();
    //     return view('dashboard.admin.book-chapters.bookChapterList', compact('chapters'));
    // }

    public function storeBook(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^(\d{10}|\d{13}|[\d\-]{10,20})$/'
            ],
            'doi' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^10\.\d{4,9}\/[-._;()\/:a-z0-9]+$/i'
            ],
            'edition' => 'required|integer|min:1',
            'genre' => 'nullable|string|max:255',
            'publisher' => 'required|string|max:255',
            'publication_date' => 'required|date',
        ]);

        Book::create($request->all());
        return response()->json(['message' => 'Book created successfully.']);
    }

    public function bookChapterMetadata(Request $request)
    {
        $chapters = BookChapter::where('status', 'ready_to_publish')->with('user')->get();
        return view('dashboard.admin.book-chapters.bookChapter', compact('chapters'));
    }

    public function storeBookChapter(Request $request)
    {
        try {
            $request->validate([
                'book_chapter_id' => 'required|exists:book_chapters,id',
                'publication_date' => 'required|date',
                'chapter_title' => 'required|string',
                'page' => 'nullable|string',
                'doi' => 'nullable|string',
                'author_first_names' => 'required|array',
                'author_first_names.*' => 'required|string',
                'author_middle_names' => 'nullable|array',
                'author_middle_names.*' => 'nullable|string',
                'author_last_names' => 'nullable|array',
                'author_last_names.*' => 'nullable|string',
                'percentile' => 'nullable|string',
            ]);

            // Build collaborations array
            $collaborations = [
                'foreign' => [
                    'author' => $request->input('author_foreign'),
                    'affiliation' => $request->input('affiliation_foreign'),
                ],
                'indian' => [
                    'author' => $request->input('author_indian'),
                    'affiliation' => $request->input('affiliation_indian'),
                ],
                'additional' => [], // Store additional authors here
            ];

            // Combine additional authors into full names
            $firstNames = $request->input('author_first_names', []);
            $middleNames = $request->input('author_middle_names', []);
            $lastNames = $request->input('author_last_names', []);
            foreach ($firstNames as $index => $firstName) {
                $middle = !empty($middleNames[$index]) ? $middleNames[$index] . ' ' : '';
                $last = !empty($lastNames[$index]) ? $lastNames[$index] : '';
                $fullName = trim($firstName . ' ' . $middle . $last);
                if ($fullName) {
                    $collaborations['additional'][] = ['author' => $fullName];
                }
            };

            // Remove empty sections
            $collaborations = array_filter($collaborations, fn($section) => !empty($section['author']) || !empty($section));

            $chapter = BookChapter::findOrFail($request->book_chapter_id);
            $chapter->update([
                'page_number' => $request->page,
                'chapter_doi' => $request->doi,
                'chapter_publication_date' => $request->input('publication_date', now()),
                'collaborations' => $collaborations ?: null, // Save as null if empty
                'status' => 'published',
            ]);

            Log::info('Chapter updated', ['paper' => $chapter->fresh()->toArray()]);

            try {
                $chapter->user->notify(new PaperStatusUpdated($chapter, 'published!', 'chapter'));
            } catch (\Exception $e) {
                Log::error('Notification failed: ' . $e->getMessage());
            }

            return response()->json([
                'message' => 'Chapter metadata saved and paper marked as published.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Store method error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Failed to save chapter metadata: ' . $e->getMessage()
            ], 500);
        }
    }

    public function publishedChapter()
    {
        $chapters = BookChapter::where('status', 'published')->with('user')->oldest()->get();

        return view('dashboard.admin.book-chapters.publishedChapters', compact('chapters'));
    }


    //Assign reviewer for chapters:
    public function index()
    {
        $today = Carbon::today();

        $reviews = BookChapterReviews::with(['reviewer', 'bookChapter'])
            ->whereNotNull('deadline')
            ->where('status', 'pending')
            ->whereDate('deadline', '>=', $today)
            ->whereDate('deadline', '<=', $today->copy()->addDays(3))
            ->get();

        foreach ($reviews as $review) {
            $lastNotified = $review->last_notified_at ? Carbon::parse($review->last_notified_at) : null;

            if (!$lastNotified || !$lastNotified->isSameDay($today)) {
                $review->reviewer->notify(new ReviewDeadlineNotification($review, 'chapter'));
                $review->update(['last_notified_at' => $today]);
            }
        }
        $chapters = BookChapter::where('status', '!=', 'published')
            ->with('user')->get();
        return view('dashboard.admin.book-chapters.bookChapterList', compact('chapters'));
    }

    public function showAssignForm($id)
    {
        $chapter = BookChapter::with('reviews')->findOrFail($id);

        $researcherInstitutionId = $chapter->user->institution_id;
        $researcherDepartmentId = $chapter->user->department_id;
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
            ->values();
        $assignedReviewers = $chapter->reviews->pluck('reviewer_id')->toArray();

        return view('dashboard.admin.assignReviewer', [
            'submission' => $chapter,
            'reviewers' => $reviewers,
            'assignedReviewers' => $assignedReviewers,
            'type' => 'chapter',
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

        $chapter = BookChapter::findOrFail($id);
        $selectedReviewerIds = $request->input('reviewers', []);
        $deadlines = $request->input('deadlines', []);


        // Remove unassigned reviews
        BookChapterReviews::where('book_chapter_id', $id)
            ->whereNotIn('reviewer_id', $selectedReviewerIds)
            ->delete();

        // Track new assignments
        $newAssignments = [];

        foreach ($selectedReviewerIds as $reviewerId) {
            $deadline = $deadlines[$reviewerId] ?? now()->addDays(7);

            $review = BookChapterReviews::firstOrCreate([
                'book_chapter_id' => $id,
                'reviewer_id' => $reviewerId,
            ]);
            $review->deadline = $deadline;
            $review->status = 'pending';
            $review->save();

            if (!$review->wasRecentlyCreated) {
                // Already existed, but we updated deadline
            } else {
                $newAssignments[] = $reviewerId;
            }
        }

        // Notify new reviewers
        foreach ($newAssignments as $reviewerId) {
            $reviewer = User::find($reviewerId);
            $reviewer->notify(new ReviewerAssignedNotification($chapter, 'chapter'));
        }

        // Update chapter status
        $remainingReviews = BookChapterReviews::where('book_chapter_id', $id)->get();

        if ($remainingReviews->isEmpty()) {
            $chapter->status = 'submitted';
        } elseif ($remainingReviews->contains('status', 'rejected')) {
            $chapter->status = 'rejected';
        } elseif ($remainingReviews->every(fn($r) => $r->status === 'approved')) {
            $chapter->status = 'approved';
        } else {
            $chapter->status = 'under_review';
        }

        $chapter->save();

        return redirect()->route('admin.bookChapters.index')->with('success', 'Reviewers updated successfully.');
    }

    public function resolveFlag(Request $request, $id)
    {
        $review = BookChapterReviews::findOrFail($id);
        $chapter = $review->bookChapter;
        $action = $request->input('action');
        $researcher = $chapter->user;
        if ($action === 'resolve') {
            $review->flagged_for_editor = false;
            $chapter->status = 'approved';
            $chapter->save();
            $review->save();
            return back()->with('success', 'Flag marked as resolved.');
        } elseif ($action === 'request_revision') {
            $review->flagged_for_editor = false;
            $chapter->status = 'revision_required';
            $chapter->save();
            $review->save();

            //notify the researcher when the chapter is updated by admin(resolve,reject, revision required)
            $researcher->notify(new PaperStatusUpdated($chapter, 'requested for revision', 'chapter'));

            return back()->with('success', 'Revision requested from researcher.');
        } elseif ($action === 'reject') {
            $review->flagged_for_editor = false;
            $review->save();
            $chapter->status = 'rejected';
            $chapter->save();
            return back()->with('success', 'chapter rejected based on editorial decision.');
        }

        return back()->with('error', 'Invalid action.');
    }

    public function showchapter($id)
    {
        $chapter = BookChapter::with(['user', 'reviews.reviewer'])->findOrFail($id);
        return view('dashboard.admin.book-chapters.viewChapter', compact('chapter'));
    }
}
