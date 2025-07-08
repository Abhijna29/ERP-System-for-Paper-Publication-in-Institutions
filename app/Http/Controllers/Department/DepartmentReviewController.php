<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\ResearchPaper;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentReviewController extends Controller
{
    public function index(Request $request)
    {
        $departmentId = Auth::user()->id;

        $departments = User::whereIn('role', ['researcher', 'reviewer'])
            ->where('department_id', $departmentId)
            ->get();

        $query = ResearchPaper::with(['category', 'subCategory', 'childCategory', 'reviews', 'reviews.reviewer', 'user'])
            ->whereHas('user', function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId);
            });

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $papers = $query->orderBy('created_at', 'desc')->get();

        $reviewsQuery = Review::with(['researchPaper', 'reviewer', 'researchPaper.user'])
            ->whereHas('researchPaper.user', function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId);
            });

        if ($request->filled('status')) {
            $reviewsQuery->where('status', $request->status);
        }

        $reviews = $reviewsQuery->latest()->get();

        return view('dashboard.department.reviewProgress', compact('papers', 'departments', 'reviews'));
    }
}
