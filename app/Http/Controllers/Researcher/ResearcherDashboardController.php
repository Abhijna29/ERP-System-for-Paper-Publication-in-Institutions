<?php

namespace App\Http\Controllers\Researcher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ResearchPaper;
use App\Models\BookChapter;
use App\Models\Invoice;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;

class ResearcherDashboardController extends Controller
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

        $userId = Auth::id();

        // Current period metrics
        $papersSubmitted = ResearchPaper::where('user_id', $userId)
            ->whereBetween('created_at', [$start, $end])->count();

        $chaptersSubmitted = BookChapter::where('user_id', $userId)
            ->whereBetween('created_at', [$start, $end])->count();

        $totalSubmitted = $papersSubmitted + $chaptersSubmitted;

        $papersAccepted = ResearchPaper::where('user_id', $userId)
            ->where('status', 'published')
            ->whereBetween('updated_at', [$start, $end])->count();

        $chaptersAccepted = BookChapter::where('user_id', $userId)
            ->where('status', 'published')
            ->whereBetween('updated_at', [$start, $end])->count();

        $totalAccepted = $papersAccepted + $chaptersAccepted;

        $invoicePayments = Invoice::where('user_id', $userId)
            ->whereBetween('created_at', [$start, $end])
            ->sum('amount');

        $subscriptionPayments = Subscription::where('user_id', $userId)
            ->whereBetween('subscriptions.created_at', [$start, $end])
            ->join('subscription_plans', 'subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
            ->sum('subscription_plans.price');

        $payments = $invoicePayments + $subscriptionPayments;

        // Previous period metrics
        $prevSubmitted = ResearchPaper::where('user_id', $userId)
            ->whereBetween('created_at', [$prevStart, $prevEnd])->count()
            + BookChapter::where('user_id', $userId)
            ->whereBetween('created_at', [$prevStart, $prevEnd])->count();

        $prevAccepted = ResearchPaper::where('user_id', $userId)
            ->where('status', 'published')
            ->whereBetween('updated_at', [$prevStart, $prevEnd])->count()
            + BookChapter::where('user_id', $userId)
            ->where('status', 'published')
            ->whereBetween('updated_at', [$prevStart, $prevEnd])->count();

        $prevInvoicePayments = Invoice::where('user_id', $userId)
            ->whereBetween('created_at', [$prevStart, $prevEnd])
            ->sum('amount');

        $prevSubscriptionPayments = Subscription::where('user_id', $userId)
            ->whereBetween('subscriptions.created_at', [$prevStart, $prevEnd])
            ->join('subscription_plans', 'subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
            ->sum('subscription_plans.price');

        $prevPayments = $prevInvoicePayments + $prevSubscriptionPayments;

        // Growth
        $submittedGrowth = $prevSubmitted > 0
            ? round((($totalSubmitted - $prevSubmitted) / $prevSubmitted) * 100, 2)
            : ($totalSubmitted > 0 ? 100 : 0);

        $acceptedGrowth = $prevAccepted > 0
            ? round((($totalAccepted - $prevAccepted) / $prevAccepted) * 100, 2)
            : ($totalAccepted > 0 ? 100 : 0);

        $paymentsGrowth = $prevPayments > 0
            ? round((($payments - $prevPayments) / $prevPayments) * 100, 2)
            : ($payments > 0 ? 100 : 0);

        // Chart data (last 11 days)
        $days = collect(range(10, 0))->map(fn($i) => Carbon::now()->subDays($i)->format('Y-m-d'));

        $chartSubmissions = $days->map(function ($day) use ($userId) {
            return ResearchPaper::where('user_id', $userId)
                ->whereDate('created_at', $day)->count()
                + BookChapter::where('user_id', $userId)
                ->whereDate('created_at', $day)->count();
        });

        $chartPayments = $days->map(function ($day) use ($userId) {
            $invoiceAmount = Invoice::where('user_id', $userId)
                ->whereDate('created_at', $day)->sum('amount');

            $subscriptionAmount = Subscription::where('user_id', $userId)
                ->whereDate('subscriptions.created_at', $day)
                ->join('subscription_plans', 'subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
                ->sum('subscription_plans.price');

            return $invoiceAmount + $subscriptionAmount;
        });

        $categories = $days->map(fn($day) => Carbon::parse($day)->format('d M'));

        $user = Auth::user();
        $latestSubscription = Subscription::where('user_id', $user->id)
            ->latest()
            ->first();

        // Determine if the subscription is expired
        $subscriptionExpired = false;

        if ($latestSubscription) {
            $subscriptionExpired = $latestSubscription->ends_at < now();
        };

        return view('dashboard.researcher.main', compact(
            'filter',
            'totalSubmitted',
            'totalAccepted',
            'payments',
            'submittedGrowth',
            'acceptedGrowth',
            'paymentsGrowth',
            'chartSubmissions',
            'chartPayments',
            'categories',
            'subscriptionExpired',
        ));
    }
}
