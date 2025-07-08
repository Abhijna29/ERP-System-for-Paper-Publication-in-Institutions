<?php

namespace App\Http\Controllers\Reviewer;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ReviewerDashboardController extends Controller
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

        $reviewerId = Auth::id();

        // Metrics
        $totalCompleted = Review::where('reviewer_id', $reviewerId)
            ->whereIn('status', ['approved', 'rejected'])
            ->whereBetween('updated_at', [$start, $end])
            ->count();

        $assignedReviews = Review::where('reviewer_id', $reviewerId)
            ->where('status', 'pending')
            ->whereBetween('updated_at', [$start, $end])
            ->count();

        // Previous metrics
        $prevCompleted = Review::where('reviewer_id', $reviewerId)
            ->whereIn('status', ['approved', 'rejected'])
            ->whereBetween('updated_at', [$prevStart, $prevEnd])
            ->count();

        $prevAssigned = Review::where('reviewer_id', $reviewerId)
            ->where('status', 'pending')
            ->whereBetween('updated_at', [$prevStart, $prevEnd])
            ->count();

        // Growth %
        $completedGrowth = $prevCompleted > 0
            ? round((($totalCompleted - $prevCompleted) / $prevCompleted) * 100, 2)
            : ($totalCompleted > 0 ? 100 : 0);

        $assignedGrowth = $prevAssigned > 0
            ? round((($assignedReviews - $prevAssigned) / $prevAssigned) * 100, 2)
            : ($assignedReviews > 0 ? 100 : 0);

        // Chart Data (last 10 days)
        $days = collect(range(9, 0))->map(fn($i) => Carbon::now()->subDays($i)->format('Y-m-d'));

        $chartCompleted = $days->map(function ($day) use ($reviewerId) {
            return Review::where('reviewer_id', $reviewerId)
                ->whereIn('status', ['approved', 'rejected'])
                ->whereBetween('updated_at', [
                    Carbon::parse($day)->startOfDay(),
                    Carbon::parse($day)->endOfDay()
                ])->count();
        });

        $chartAssigned = $days->map(function ($day) use ($reviewerId) {
            return Review::where('reviewer_id', $reviewerId)
                ->where('status', 'pending')
                ->whereBetween('updated_at', [
                    Carbon::parse($day)->startOfDay(),
                    Carbon::parse($day)->endOfDay()
                ])->count();
        });

        $categories = $days->map(fn($day) => Carbon::parse($day)->format('d M'));

        return view('dashboard.reviewer.main', compact(
            'totalCompleted',
            'assignedReviews',
            'completedGrowth',
            'assignedGrowth',
            'chartCompleted',
            'chartAssigned',
            'categories',
            'filter'
        ));
    }
}
