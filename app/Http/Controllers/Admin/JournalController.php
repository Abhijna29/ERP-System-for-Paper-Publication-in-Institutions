<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ResearchPaper;
use App\Models\User;
use App\Notifications\PaperStatusUpdated;
use App\Notifications\SubmissionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JournalController extends Controller
{
    public function scopusSelect()
    {
        $papers = ResearchPaper::where('status', 'ready_to_publish')->get();
        return view('dashboard.admin.journals.scopus', compact('papers'));
    }

    public function scopus($paperId)
    {
        $paper = ResearchPaper::findOrFail($paperId);
        $papers = ResearchPaper::where('status', 'ready_to_publish')->get();
        return view('dashboard.admin.journals.scopus', compact('paper', 'papers'));
    }

    public function webOfScienceSelect()
    {
        $papers = ResearchPaper::where('status', 'ready_to_publish')->get();
        return view('dashboard.admin.journals.webOfScience', compact('papers'));
    }

    public function webOfScience($paperId)
    {
        $paper = ResearchPaper::findOrFail($paperId);
        return view('dashboard.admin.journals.webOfScience', compact('paper'));
    }

    public function pubMedSelect()
    {
        $papers = ResearchPaper::where('status', 'ready_to_publish')->get();
        return view('dashboard.admin.journals.pubMed', compact('papers'));
    }

    public function pubMed($paperId)
    {
        $paper = ResearchPaper::findOrFail($paperId);
        return view('dashboard.admin.journals.pubMed', compact('paper'));
    }

    public function abdcSelect()
    {
        $papers = ResearchPaper::where('status', 'ready_to_publish')->get();
        return view('dashboard.admin.journals.abdc', compact('papers'));
    }

    public function abdc($paperId)
    {
        $paper = ResearchPaper::findOrFail($paperId);
        return view('dashboard.admin.journals.abdc', compact('paper'));
    }

    public function otherSelect()
    {
        $papers = ResearchPaper::where('status', 'ready_to_publish')->get();
        return view('dashboard.admin.journals.other', compact('papers'));
    }

    public function other($paperId)
    {
        $paper = ResearchPaper::findOrFail($paperId);
        return view('dashboard.admin.journals.other', compact('paper'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'paper_id' => 'required|exists:research_papers,id',
                'indexing_database' => 'required|string',
                'publication_date' => 'required|date',
                'title' => 'required|string',
                'source' => 'required|string',
                'volume' => 'nullable|string',
                'issue' => 'nullable|string',
                'page' => 'nullable|string',
                'doi' => 'nullable|string',
                'percentile' => 'nullable|string',
                'authors' => 'required|array|min:1',
                'authors.*' => 'required|string|max:255',
            ]);

            $authors = $request->input('authors', []);
            $collaborations = [];

            foreach ($authors as $authorName) {
                if (!empty($authorName)) {
                    $collaborations['additional'][] = ['author' => trim($authorName)];
                }
            }


            // Remove empty sections
            $collaborations = array_filter($collaborations, fn($section) => !empty($section['author']) || !empty($section));

            $indexingDatabase = $request->input('indexing_database');
            if ($indexingDatabase === 'others') {
                $indexingDatabase = $request->input('db'); // use the actual entered name
            }

            $paper = ResearchPaper::findOrFail($request->paper_id);
            $paper->update([
                'source' => $request->source,
                'volume_number' => $request->volume,
                'issue_number' => $request->issue,
                'page_number' => $request->page,
                'doi' => $request->doi,
                'publication_date' => $request->input('publication_date', now()),
                'collaborations' => $collaborations ?: null, // Save as null if empty
                'indexing_database' => $indexingDatabase,
                'status' => 'published',
                'percentile' => $request->percentile,
            ]);

            // Log::info('Paper updated', ['paper' => $paper->fresh()->toArray()]);

            try {
                $paper->user->notify(new PaperStatusUpdated($paper, 'published!', 'paper'));
                $institutionUser = User::where('role', 'institution')
                    ->where('id', $paper->user->institution_id)
                    ->first();

                $departmentUser = User::where('role', 'department')
                    ->where('id', $paper->user->department_id)
                    ->first();

                if ($institutionUser) {
                    $institutionUser->notify(new SubmissionNotification($paper, 'paper'));
                }

                if ($departmentUser) {
                    $departmentUser->notify(new SubmissionNotification($paper, 'paper'));
                }
            } catch (\Exception $e) {
                Log::error('Notification failed: ' . $e->getMessage());
            }

            return response()->json([
                'message' => 'Journal metadata saved and paper marked as published.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Store method error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Failed to save journal metadata: ' . $e->getMessage()
            ], 500);
        }
    }
}
