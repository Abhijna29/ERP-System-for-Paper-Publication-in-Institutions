<?php

namespace App\Http\Controllers\Researcher;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\ResearchPaper;
use App\Models\SubCategory;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\PaperResubmittedNotification;
use App\Notifications\SubmissionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ResearchPaperController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $researcher = Auth::user();
        $institutionId = $researcher->institution_id;
        $subscription = Subscription::where('user_id', $institutionId)
            ->where('ends_at', '>=', now())
            ->latest()
            ->first();

        // Pass a flag if no subscription
        $hasSubscription = $subscription !== null;
        $categories = Category::all();

        return view('dashboard.researcher.submitPaper', compact('categories', 'subscription', 'hasSubscription'));
    }

    /**
     * Store a newly created resource in storage.
     */
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

        // Validate input
        $request->validate([
            'title' => ['required', 'string', function ($attribute, $value, $fail) {
                $wordCount = str_word_count($value);
                if ($wordCount > 15) {
                    $fail('The title must not exceed 15 words. You have ' . $wordCount . '.');
                }
            }],
            'abstract' => ['required', 'string', function ($attribute, $value, $fail) {
                $wordCount = str_word_count($value);
                if ($wordCount > 250) {
                    $fail('The abstract must not exceed 250 words. You have ' . $wordCount . '.');
                }
            }],
            'keywords' => ['required', 'string', function ($attribute, $value, $fail) {
                $keywords = array_filter(array_map('trim', explode(',', $value)));
                $wordCount = count($keywords);
                if ($wordCount > 10) {
                    $fail('The keywords field must not exceed 10 keywords. You have ' . $wordCount . '.');
                }
            }],
            'category' => 'required|exists:categories,id',
            'subCategory' => 'required|exists:sub_categories,id',
            'childCategory' => 'required|exists:child_categories,id',
            'paper_file' => ['required', 'mimes:pdf', 'max:20480'],
        ]);

        // Clean up unused files
        $papersInDb = ResearchPaper::all();
        $filesInFolder = collect(Storage::files('public/papers'))->map(fn($file) => basename($file));
        $filesToDelete = $filesInFolder->filter(fn($file) => !$papersInDb->contains('file_path', 'papers/' . $file));
        foreach ($filesToDelete as $file) {
            Storage::delete('public/papers/' . $file);
        }

        // Store uploaded file
        $file = $request->file('paper_file');
        $titleSlug = Str::slug($request->title);
        $filename = $titleSlug . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('papers', $filename, 'public');

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

        // Save research paper
        $paper = ResearchPaper::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'abstract' => $request->abstract,
            'keywords' => $request->keywords,
            'file_path' => $filePath,
            'category_id' => $request->category,
            'sub_category_id' => $request->subCategory,
            'child_category_id' => $request->childCategory,
            'status' => 'submitted',
            'collaborations' => $collaborations ?: null,
        ]);

        $researcher = Auth::user();

        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new SubmissionNotification($paper, 'paper'));
        }

        $institutionUser = User::where('role', 'institution')
            ->where('id', $researcher->institution_id)
            ->first();

        $departmentUser = User::where('role', 'department')
            ->where('id', $researcher->department_id)
            ->first();

        if ($institutionUser) {
            $institutionUser->notify(new SubmissionNotification($paper, 'paper'));
        }

        if ($departmentUser) {
            $departmentUser->notify(new SubmissionNotification($paper, 'paper'));
        }

        // Update subscription usage
        $subscription->increment('papers_used');

        return redirect()->route('papers.submitted')->with('submitted', true)->with('paper', $paper);
    }


    public function submitted()
    {
        $papers = ResearchPaper::with(['category', 'subCategory', 'childCategory', 'reviews.reviewer'])
            ->where('user_id', Auth::id())
            ->get();
        return view('dashboard.researcher.viewPaperSubmitted', compact('papers'));
    }

    public function resubmit(Request $request, $id)
    {
        $paper = ResearchPaper::findOrFail($id);

        if ($paper->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'resubmission_file' => ['required', 'mimes:pdf', 'max:20480'],
        ]);

        // Delete old file if needed
        if ($paper->file_path && Storage::disk('public')->exists($paper->file_path)) {
            Storage::disk('public')->delete($paper->file_path);
        }

        $filePath = $request->file('resubmission_file')->store('papers', 'public');
        $paper->file_path = $filePath;
        $paper->status = 'resubmitted';
        $paper->resubmission_count += 1;
        $paper->save();
        $review = $paper->reviews()->latest()->first();

        $reviewsToUpdate = $paper->reviews()->where('status', 'revision_required')->get();

        foreach ($reviewsToUpdate as $review) {
            $review->status = 'resubmitted';
            $review->comments = null;
            $review->save();

            if ($review->reviewer) {
                $review->reviewer->notify(new PaperResubmittedNotification($paper, 'paper'));
            }
        }

        // return back()->with('success', 'Paper resubmitted successfully.');
        return redirect()->route('papers.submitted')->with('submitted', true)->with('paper', $paper);
    }

    public function getSubCategories($categoryId)
    {
        $subCategories = SubCategory::where('category_id', $categoryId)->get(['id', 'name']);
        return response()->json($subCategories);
    }

    public function getChildCategories($subCategoryId)
    {
        $childCategories = ChildCategory::where('sub_category_id', $subCategoryId)->get(['id', 'name']);
        return response()->json($childCategories);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResearchPaper $researchPaper)
    {
        // Delete the associated file from storage
        if ($researchPaper->file_path && Storage::disk('public')->exists($researchPaper->file_path)) {
            Storage::disk('public')->delete($researchPaper->file_path);
        }

        // Delete the paper from the database
        $researchPaper->delete();

        return redirect()->back()->with('success', 'Research paper deleted successfully.');
    }
}
