<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Models\ResearchPaper;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InstitutionReviewController extends Controller
{
    public function index(Request $request)
    {
        $institutionId = Auth::user()->id; // Assuming the logged-in user is the institution

        // Fetch departments (users with role 'department') for the institution
        $departments = User::where('role', 'department')
            ->where('institution_id', $institutionId)
            ->get();

        // Build the query for papers
        $query = ResearchPaper::with(['category', 'subCategory', 'childCategory', 'reviews', 'reviews.reviewer', 'user'])
            ->whereHas('user', function ($query) use ($institutionId) {
                $query->where('institution_id', $institutionId);
            });

        // Apply department filter if provided
        if ($request->filled('department')) {
            $query->whereHas('user', function ($query) use ($request) {
                $query->where('department_id', $request->department);
            });
        }

        // Apply status filter if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Fetch papers with filters applied
        $papers = $query->orderBy('created_at', 'desc')->get();

        // Fetch reviews for papers belonging to the institution
        $reviews = Review::with(['researchPaper', 'reviewer', 'researchPaper.user'])
            ->whereHas('researchPaper.user', function ($query) use ($institutionId) {
                $query->where('institution_id', $institutionId);
            })
            ->latest()
            ->get();

        return view('dashboard.institution.reviewProgress', compact('papers', 'departments', 'reviews'));
    }
}
