<?php

namespace App\Http\Controllers\Researcher;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookChapter;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\chapterResubmittedNotification;
use App\Notifications\PaperResubmittedNotification;
use App\Notifications\SubmissionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\RevSummarizer;

class BookChapterController extends Controller
{
    public function create()
    {
        $books = Book::all();
        $genres = Book::select('genre')->distinct()->whereNotNull('genre')->pluck('genre');
        $researcher = Auth::user();
        $institutionId = $researcher->institution_id;
        $subscription = Subscription::where('user_id', $institutionId)
            ->where('ends_at', '>=', now())
            ->latest()
            ->first();

        // Pass a flag if no subscription
        $hasSubscription = $subscription !== null;
        return view('dashboard.researcher.submitBookChapter', compact('books', 'genres', 'subscription', 'hasSubscription'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $institutionId = $user->institution_id;

        $subscription = Subscription::where('user_id', $institutionId)
            ->where('ends_at', '>=', now())
            ->latest()
            ->first();

        if (!$subscription) {
            return redirect()->back()->with('error', 'Your institution does not have an active subscription. Please contact your institution administrator.');
        }

        // Check paper submission limit
        if ($subscription->papers_used >= $subscription->plan->paper_limit) {
            return redirect()->back()->with('error', 'Your institution has reached its submission limit.');
        }

        $request->validate([
            'chapter_title' => 'required|string',
            'keywords' => 'nullable|string',
            'genre' => 'nullable|string',
            'chapter_file' => 'required|mimes:pdf|max:10240',
            'book_id' => 'nullable|exists:books,id',
        ]);

        $filePath = $request->file('chapter_file')->store('book_chapters', 'public');

        $foreignAuthors = $request->input('author_foreign', []);
        $foreignAffiliations = $request->input('affiliation_foreign', []);
        $indianAuthors = $request->input('author_indian', []);
        $indianAffiliations = $request->input('affiliation_indian', []);

        $foreign = [];
        for ($i = 0; $i < count($foreignAuthors); $i++) {
            if (!empty($foreignAuthors[$i]) || !empty($foreignAffiliations[$i])) {
                $foreign[] = [
                    'author' => $foreignAuthors[$i] ?? '',
                    'affiliation' => $foreignAffiliations[$i] ?? '',
                ];
            }
        }

        $indian = [];
        for ($i = 0; $i < count($indianAuthors); $i++) {
            if (!empty($indianAuthors[$i]) || !empty($indianAffiliations[$i])) {
                $indian[] = [
                    'author' => $indianAuthors[$i] ?? '',
                    'affiliation' => $indianAffiliations[$i] ?? '',
                ];
            }
        }

        $collaborations = [
            'foreign' => $foreign,
            'indian' => $indian,
        ];

        $chapter = BookChapter::create([
            'user_id' => $user->id,
            'book_id' => $request->book_id,
            'chapter_title' => $request->chapter_title,
            'keywords' => $request->keywords,
            'genre' => $request->genre,
            'file_path' => $filePath,
            'status' => 'submitted',
            'collaborations' => $collaborations ?: null,
        ]);

        $researcher = Auth::user();
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new SubmissionNotification($chapter, 'chapter'));
        }
        $institutionUser = User::where('role', 'institution')
            ->where('id', $researcher->institution_id)
            ->first();

        $departmentUser = User::where('role', 'department')
            ->where('id', $researcher->department_id)
            ->first();

        if ($institutionUser) {
            $institutionUser->notify(new SubmissionNotification($chapter, 'chapter'));
        }

        if ($departmentUser) {
            $departmentUser->notify(new SubmissionNotification($chapter, 'chapter'));
        }

        $subscription->increment('papers_used');

        return redirect()->route('chapters.submitted')->with('success', 'Book chapter submitted successfully.');
    }

    public function chapterSubmitted()
    {
        $chapters = BookChapter::with(['book', 'reviews.reviewer'])->where('user_id', Auth::id())->get();
        return view('dashboard.researcher.viewChapterSubmitted', compact('chapters'));
    }

    public function resubmit(Request $request, $id)
    {
        $chapter = BookChapter::findOrFail($id);

        if ($chapter->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'resubmission_file' => ['required', 'mimes:pdf', 'max:10240'],
        ]);

        // Delete old file if needed
        if ($chapter->file_path && Storage::disk('public')->exists($chapter->file_path)) {
            Storage::disk('public')->delete($chapter->file_path);
        }

        $filePath = $request->file('resubmission_file')->store('book_chapters', 'public');

        $chapter->file_path = $filePath;
        $chapter->status = 'resubmitted';
        $chapter->resubmission_count += 1;
        $chapter->save();

        $review = $chapter->reviews()->latest()->first();
        $reviewsToUpdate = $chapter->reviews()->where('status', 'revision_required')->get();

        foreach ($reviewsToUpdate as $review) {
            $review->status = 'resubmitted';
            $review->comments = null;
            $review->save();

            if ($review->reviewer) {
                $review->reviewer->notify(new PaperResubmittedNotification($chapter, 'chapter'));
            }
        }

        return redirect()->route('chapters.submitted')->with('success', 'Book chapter resubmitted successfully.');
    }
    public function booksByGenre($genre)
    {
        $books = Book::where('genre', $genre)->get(['id', 'title']);
        return response()->json($books);
    }

    public function destroy(BookChapter $bookChapter)
    {
        // Delete the associated file from storage
        if ($bookChapter->file_path && Storage::disk('public')->exists($bookChapter->file_path)) {
            Storage::disk('public')->delete($bookChapter->file_path);
        }

        // Delete the chapter from the database
        $bookChapter->delete();

        return redirect()->back()->with('success', 'Research chapter deleted successfully.');
    }
}
