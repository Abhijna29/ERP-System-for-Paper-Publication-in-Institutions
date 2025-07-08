<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\ResearchPaper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\BookChapter; // Don't forget this line

class InstitutionDashboardController extends Controller
{

    public function index(Request $request)
    {
        $filter = $request->input('filter', 'month');

        switch ($filter) {
            case 'today':
                $start = Carbon::today();
                $end = Carbon::today()->endOfDay();
                $prevStart = Carbon::yesterday()->startOfDay();
                $prevEnd = Carbon::yesterday()->endOfDay();
                break;
            case 'year':
                $start = Carbon::now()->startOfYear();
                $end = Carbon::now()->endOfYear();
                $prevStart = Carbon::now()->subYear()->startOfYear();
                $prevEnd = Carbon::now()->subYear()->endOfYear();
                break;
            case 'month':
            default:
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                $prevStart = Carbon::now()->subMonth()->startOfMonth();
                $prevEnd = Carbon::now()->subMonth()->endOfMonth();
                break;
        }

        $institutionId = Auth::id();

        $researcherIds = User::where('role', 'researcher')
            ->where('institution_id', $institutionId)
            ->pluck('id');

        // ✅ Metrics: Accepted
        $papersAccepted = ResearchPaper::whereIn('user_id', $researcherIds)
            ->where('status', 'published')
            ->whereBetween('updated_at', [$start, $end])
            ->count();

        $chaptersAccepted = BookChapter::whereIn('user_id', $researcherIds)
            ->where('status', 'published')
            ->whereBetween('updated_at', [$start, $end])
            ->count();

        $totalAccepted = $papersAccepted + $chaptersAccepted;

        // ✅ Metrics: Rejected
        $papersRejected = ResearchPaper::whereIn('user_id', $researcherIds)
            ->where('status', 'rejected')
            ->whereBetween('updated_at', [$start, $end])
            ->count();

        $chaptersRejected = BookChapter::whereIn('user_id', $researcherIds)
            ->where('status', 'rejected')
            ->whereBetween('updated_at', [$start, $end])
            ->count();

        $totalRejected = $papersRejected + $chaptersRejected;

        // ✅ Researchers Registered
        $registeredResearchers = User::where('role', 'researcher')
            ->where('institution_id', $institutionId)
            ->whereBetween('created_at', [$start, $end])
            ->count();

        // ✅ Previous period for growth comparison
        $prevAccepted = ResearchPaper::whereIn('user_id', $researcherIds)
            ->where('status', 'published')
            ->whereBetween('updated_at', [$prevStart, $prevEnd])
            ->count()
            + BookChapter::whereIn('user_id', $researcherIds)
            ->where('status', 'published')
            ->whereBetween('updated_at', [$prevStart, $prevEnd])
            ->count();

        $prevRejected = ResearchPaper::whereIn('user_id', $researcherIds)
            ->where('status', 'rejected')
            ->whereBetween('updated_at', [$prevStart, $prevEnd])
            ->count()
            + BookChapter::whereIn('user_id', $researcherIds)
            ->where('status', 'rejected')
            ->whereBetween('updated_at', [$prevStart, $prevEnd])
            ->count();

        $prevResearchers = User::where('role', 'researcher')
            ->where('institution_id', $institutionId)
            ->whereBetween('created_at', [$prevStart, $prevEnd])
            ->count();

        // ✅ Growth
        $acceptedGrowth = $prevAccepted > 0 ? round((($totalAccepted - $prevAccepted) / $prevAccepted) * 100, 2) : ($totalAccepted > 0 ? 100 : 0);
        $rejectedGrowth = $prevRejected > 0 ? round((($totalRejected - $prevRejected) / $prevRejected) * 100, 2) : ($totalRejected > 0 ? 100 : 0);
        $researcherGrowth = $prevResearchers > 0 ? round((($registeredResearchers - $prevResearchers) / $prevResearchers) * 100, 2) : ($registeredResearchers > 0 ? 100 : 0);

        // ✅ Chart Data (Last 10 days)
        $days = collect(range(9, 0))->map(fn($i) => Carbon::now()->subDays($i)->format('Y-m-d'));

        $chartAccepted = $days->map(
            fn($day) =>
            ResearchPaper::whereIn('user_id', $researcherIds)->where('status', 'published')->whereDate('updated_at', $day)->count()
                + BookChapter::whereIn('user_id', $researcherIds)->where('status', 'published')->whereDate('updated_at', $day)->count()
        );

        $chartRejected = $days->map(
            fn($day) =>
            ResearchPaper::whereIn('user_id', $researcherIds)->where('status', 'rejected')->whereDate('updated_at', $day)->count()
                + BookChapter::whereIn('user_id', $researcherIds)->where('status', 'rejected')->whereDate('updated_at', $day)->count()
        );

        $chartResearchers = $days->map(
            fn($day) =>
            User::where('role', 'researcher')->where('institution_id', $institutionId)->whereDate('created_at', $day)->count()
        );

        $categories = $days->map(fn($day) => Carbon::parse($day)->format('d M'));

        return view('dashboard.institution.main', compact(
            'filter',
            'registeredResearchers',
            'totalAccepted',
            'totalRejected',
            'acceptedGrowth',
            'rejectedGrowth',
            'researcherGrowth',
            'chartAccepted',
            'chartRejected',
            'chartResearchers',
            'categories'
        ));
    }
}
