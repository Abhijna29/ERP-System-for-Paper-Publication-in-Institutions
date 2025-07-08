<?php

namespace App\Http\Controllers\Researcher;

use App\Http\Controllers\Controller;
use App\Models\BookChapter;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\ResearchPaper;
use App\Models\User;
use App\Notifications\PaymentSuccessfulNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Razorpay\Api\Api;

class ResearcherController extends Controller
{
    public function myInvoices()
    {
        $invoices = Invoice::where('user_id', Auth::user()->id)->oldest()->get();
        return view('dashboard.researcher.invoices.index', compact('invoices'));
    }

    public function viewInvoice($id)
    {
        $invoice = Invoice::where('user_id', Auth::user()->id)->findOrFail($id);
        return view('dashboard.researcher.invoices.view', compact('invoice'));
    }



    public function payInvoice($id)
    {
        $invoice = Invoice::where('user_id', Auth::id())->where('status', 'unpaid')->findOrFail($id);

        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        $razorpayOrder = $api->order->create([
            'receipt' => 'INV#' . $invoice->id,
            'amount' => $invoice->amount * 100, // in paise
            'currency' => 'INR'
        ]);
        if (!isset($razorpayOrder['id'])) {
            dd($razorpayOrder); // Show error from Razorpay API
        }

        $order_id = $razorpayOrder['id'];

        session()->put('razorpay_order_id', $order_id);

        return view('dashboard.researcher.invoices.pay', compact('invoice', 'order_id'));
    }

    public function submitInvoicePayment(Request $request, $id)
    {
        $request->validate([
            'card_number' => ['required', 'digits:16'],
            'expiry' => ['required', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
            'cvv' => ['required', 'digits_between:3,4'],
        ]);

        $invoice = Invoice::where('user_id', Auth::user()->id)
            ->where('status', 'unpaid')
            ->findOrFail($id);

        // Update invoice status
        $invoice->status = 'paid';
        $invoice->save();

        // Record payment
        Payment::create([
            'user_id' => Auth::id(),
            'invoice_id' => $invoice->id,
            'amount' => $invoice->amount,
            'status' => 'paid',
            'payment_method' => 'card',
        ]);
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new PaymentSuccessfulNotification($invoice));
        }

        // Update paper or chapter status
        if ($invoice->research_paper_id) {
            $paper = ResearchPaper::find($invoice->research_paper_id);
            if ($paper && $paper->status === 'pending_payment') {
                $paper->status = 'ready_to_publish';
                $paper->save();
            }
        }
        if ($invoice->book_chapter_id) {
            $chapter = BookChapter::find($invoice->book_chapter_id);
            if ($chapter && $chapter->status === 'pending_payment') {
                $chapter->status = 'ready_to_publish';
                $chapter->save();
            }
        }

        return redirect()->route('researcher.invoices')->with('success', 'Payment successful. Submission is now ready to publish.');
    }

    public function razorpaySuccess(Request $request, $id)
    {
        $invoice = Invoice::where('user_id', Auth::id())
            ->where('status', 'unpaid')
            ->findOrFail($id);

        // Update invoice status
        $invoice->update(['status' => 'paid']);

        // Record payment
        Payment::create([
            'user_id' => Auth::id(),
            'invoice_id' => $invoice->id,
            'amount' => $invoice->amount,
            'status' => 'paid',
            'payment_gateway' => 'Razorpay',
        ]);

        // Update paper status
        if ($invoice->research_paper_id) {
            $paper = ResearchPaper::find($invoice->research_paper_id);
            if ($paper && $paper->status === 'pending_payment') {
                $paper->status = 'ready_to_publish';
                $paper->save();
            }
        }
        if ($invoice->book_chapter_id) {
            $chapter = \App\Models\BookChapter::find($invoice->book_chapter_id);
            if ($chapter && $chapter->status === 'pending_payment') {
                $chapter->status = 'ready_to_publish';
                $chapter->save();
            }
        }
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new PaymentSuccessfulNotification($invoice));
        }

        return redirect()->route('researcher.invoices')->with('success', 'Payment successful via Razorpay. Paper is now ready to publish.');
    }
}
