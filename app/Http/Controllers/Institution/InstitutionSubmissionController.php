<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Models\ResearchPaper;
use App\Models\BookChapter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstitutionSubmissionController extends Controller
{
    public function index(Request $request, $type = 'paper')
    {
        $institutionId = Auth::user()->id;

        $departments = User::where('role', 'department')
            ->where('institution_id', $institutionId)
            ->get();

        if ($type === 'chapter') {
            $model = BookChapter::query()->with(['book', 'user']);
            $researcherRelation = 'user';
        } else {
            $model = ResearchPaper::query()->with(['category', 'subCategory', 'childCategory', 'reviews']);
            $researcherRelation = 'researcher';
        }

        $model->whereHas($researcherRelation, function ($query) use ($institutionId) {
            $query->where('institution_id', $institutionId);
        });

        if ($request->filled('department')) {
            $model->whereHas($researcherRelation, function ($query) use ($request) {
                $query->where('department_id', $request->department);
            });
        }

        if ($request->filled('status')) {
            $model->where('status', $request->status);
        }

        $submissions = $model->orderBy('created_at', 'desc')->get();

        $view = $type === 'chapter'
            ? 'dashboard.institution.chapters.index'
            : 'dashboard.institution.papers.index';

        return view($view, [
            'submissions' => $submissions,
            'departments' => $departments,
            'type' => $type,
        ]);
    }

    public function show($type, $id)
    {
        if ($type === 'chapter') {
            $submission = BookChapter::with(['book', 'user'])->findOrFail($id);
            $view = 'dashboard.institution.chapters.showChapter';
        } else {
            $submission = ResearchPaper::with(['researcher', 'department', 'reviews'])->findOrFail($id);
            $view = 'dashboard.institution.papers.showPaper';
        }
        return view($view, ['submission' => $submission, 'type' => $type]);
    }

    public function updateStatus(Request $request, $type, $id)
    {
        if ($type === 'chapter') {
            $submission = BookChapter::findOrFail($id);
        } else {
            $submission = ResearchPaper::findOrFail($id);
        }
        $submission->status = $request->status;
        $submission->save();

        return redirect()->back()->with('success', 'Status updated.');
    }
}
