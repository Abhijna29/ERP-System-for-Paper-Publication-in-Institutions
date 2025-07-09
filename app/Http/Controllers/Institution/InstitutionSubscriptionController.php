<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Models\Download;
use App\Models\ResearchPaper;
use App\Models\BookChapter;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class InstitutionSubscriptionController extends Controller
{
    public function showPlans()
    {
        $role = Auth::user()->role;
        $plans = SubscriptionPlan::all();
        return view('dashboard..subscriptions.subscriptionPlans', compact('plans', 'role'));
    }

    public function subscribe(Request $request)
    {
        $plan = SubscriptionPlan::findOrFail($request->plan_id);
        $user = Auth::user();
        $role = Auth::user()->role;

        $orderId = 'SUB' . now()->timestamp . $user->id;
        $gstRate = 0.18;
        $priceWithGst = $plan->price + ($plan->price * $gstRate);

        $razorpayOrder = [
            'amount' => round($priceWithGst * 100),
            'currency' => 'INR',
            'receipt' => $orderId,
            'payment_capture' => 1,
        ];

        $api = new \Razorpay\Api\Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        $razorpayOrder = $api->order->create($razorpayOrder);

        session([
            'plan_id' => $plan->id,
            'razorpay_order_id' => $razorpayOrder['id'],
            'plan_duration' => $plan->duration,
        ]);

        return view('dashboard.subscriptions.razorpayCheckout', [
            'order' => $razorpayOrder,
            'plan' => $plan,
            'user' => $user,
            'key' => config('services.razorpay.key'),
            'role' => $role,
        ]);
    }

    public function paymentSuccess(Request $request)
    {
        $user = Auth::user();
        $planId = session('plan_id');
        $duration = session('plan_duration');

        $plan = SubscriptionPlan::findOrFail($planId);
        $months = match ($duration) {
            '1 Month' => 1,
            '3 Months' => 3,
            '6 Months' => 6,
            '12 Months' => 12,
            default => 1,
        };

        $gstAmount = $plan->price * 0.18;
        $totalAmount = $plan->price + $gstAmount;

        Subscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $planId,
            'starts_at' => now(),
            'ends_at' => now()->addMonths($months),
            'amount' => $totalAmount,
        ]);

        session()->forget(['plan_id', 'plan_duration', 'razorpay_order_id']);

        return redirect()->route('subscription.mine')->with('success', 'Subscription activated successfully!');
    }

    public function mySubscriptions()
    {
        $user = Auth::user();
        $subscriptions = Subscription::with('plan')
            ->where('user_id', $user->id)
            ->orderByDesc('starts_at')
            ->get();

        $role = $user->role;

        return view('dashboard.subscriptions.mySubscriptions', compact('subscriptions', 'role'));
    }

    public function download(Request $request, $type, $id)
    {
        $user = Auth::user();

        $model = $type === 'chapters' ? BookChapter::class : ResearchPaper::class;
        $content = $model::with('reviews')->findOrFail($id);

        $isPublished = $content->status === 'published';
        $isAuthor = $content->user_id === $user->id;
        $isAdminOrInstitution = in_array($user->role, ['admin', 'institution']);
        $isReviewer = $user->role === 'reviewer';
        $isAssignedReviewer = $isReviewer && method_exists($content, 'reviews') && $content->reviews->where('reviewer_id', $user->id)->isNotEmpty();

        $hasInstitutionSubscription = false;
        $institutionSubscription = null;

        if ($user->role === 'researcher' && !$isAuthor && $isPublished) {
            $institutionId = $user->institution_id;

            $institutionSubscription = Subscription::where('user_id', $institutionId)
                ->where('ends_at', '>=', now())
                ->latest()
                ->first();

            $hasInstitutionSubscription = $institutionSubscription !== null;
        }

        $canDownload = $isPublished || $isAuthor || $isAdminOrInstitution || $isAssignedReviewer || $hasInstitutionSubscription;

        if (!$canDownload) {
            return redirect()->back()->with('error', 'You are not authorized to download this content.');
        }

        $alreadyDownloaded = Download::where('user_id', $user->id)
            ->where('research_paper_id', $type === 'chapters' ? null : $content->id)
            ->where('book_chapter_id', $type === 'chapters' ? $content->id : null)
            ->exists();

        if ($hasInstitutionSubscription && !$isAuthor && !$alreadyDownloaded) {
            if ($institutionSubscription->downloads_used >= $institutionSubscription->plan->download_limit) {
                return back()->with('error', 'Your institution has reached the maximum number of downloads.');
            }

            $institutionSubscription->downloads_used += 1;
            $institutionSubscription->save();

            Download::create([
                'user_id' => $user->id,
                'research_paper_id' => $type === 'chapters' ? null : $content->id,
                'book_chapter_id' => $type === 'chapters' ? $content->id : null,
                'downloaded_at' => now(),
            ]);
        }

        $filePath = storage_path("app/public/{$content->file_path}");
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        return Response::download($filePath, basename($filePath));
    }


    public function redirectToSubscription()
    {
        $role = Auth::user()->role;
        return view('dashboard.subscriptions.subscriptionRedirect', compact('role'));
    }
}
