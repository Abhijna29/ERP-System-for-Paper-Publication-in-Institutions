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
        $institutionId = Auth::user()->id;

        $departments = User::where('role', 'department')
            ->where('institution_id', $institutionId)
            ->get();

        $query = ResearchPaper::with(['category', 'subCategory', 'childCategory', 'reviews', 'reviews.reviewer', 'user'])
            ->whereHas('user', function ($query) use ($institutionId) {
                $query->where('institution_id', $institutionId);
            });

        if ($request->filled('department')) {
            $query->whereHas('user', function ($query) use ($request) {
                $query->where('department_id', $request->department);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $papers = $query->orderBy('created_at', 'desc')->get();

        $reviews = Review::with(['researchPaper', 'reviewer', 'researchPaper.user'])
            ->whereHas('researchPaper.user', function ($query) use ($institutionId) {
                $query->where('institution_id', $institutionId);
            })
            ->latest()
            ->get();

        return view('dashboard.institution.reviewProgress', compact('papers', 'departments', 'reviews'));
    }
}
