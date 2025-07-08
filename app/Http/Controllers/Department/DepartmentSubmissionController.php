<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\BookChapter;
use App\Models\ResearchPaper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentSubmissionController extends Controller
{
    public function index(Request $request, $type = 'paper')
    {
        $deptID = Auth::user()->id;

        // Fetch departments for the department
        $departments = User::where('role', ['researcher', 'reviewer'])
            ->where('department_id', $deptID)
            ->get();

        // Choose model and relationships based on type
        if ($type === 'chapter') {
            $model = BookChapter::query()->with(['book', 'user']);
            $researcherRelation = 'user';
        } else {
            $model = ResearchPaper::query()->with(['category', 'subCategory', 'childCategory', 'reviews']);
            $researcherRelation = 'researcher';
        }

        // Filter by department
        $model->whereHas($researcherRelation, function ($query) use ($deptID) {
            $query->where('department_id', $deptID);
        });

        // Department filter
        if ($request->filled('department')) {
            $model->whereHas($researcherRelation, function ($query) use ($request) {
                $query->where('department_id', $request->department);
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $model->where('status', $request->status);
        }

        $submissions = $model->orderBy('created_at', 'desc')->get();

        // Choose view based on type
        $view = $type === 'chapter'
            ? 'dashboard.department.chapters.index'
            : 'dashboard.department.papers.index';

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
            $view = 'dashboard.department.chapters.showChapter';
        } else {
            $submission = ResearchPaper::with(['researcher', 'department', 'reviews'])->findOrFail($id);
            $view = 'dashboard.department.papers.showPaper';
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
