<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\ResearchPaper;
use App\Models\BookChapter;
use App\Notifications\InvoiceGeneratedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApproveController extends Controller
{
    public function approveSubmission($type, $id)
    {
        if ($type === 'paper') {
            $submission = ResearchPaper::findOrFail($id);
            $user = $submission->user;
            $title = $submission->title;
            $descType = 'paper';
            $foreignKey = 'research_paper_id';
        } else {
            $submission = BookChapter::findOrFail($id);
            $user = $submission->user;
            $title = $submission->chapter_title;
            $descType = 'book chapter';
            $foreignKey = 'book_chapter_id';
        }

        if ($submission->status === 'under_review') {
            return back()->with('error', ucfirst($descType) . ' is not ready for approval.');
        }

        $submission->status = 'pending_payment';
        $submission->save();

        $invoiceData = [
            'user_id' => $user->id,
            'invoice_number' => 'INV-' . strtoupper(uniqid()),
            'amount' => 1000.00,
            'status' => 'unpaid',
            'description' => 'Publication Fee for ' . $descType . ': ' . $title,
            'invoice_date' => now(),
            'due_date' => now()->addDays(7),
        ];

        // ✅ Dynamically add the correct foreign key
        $invoiceData[$foreignKey] = $submission->id;

        Log::info('Invoice Data:', $invoiceData); // For debug

        $invoice = Invoice::create($invoiceData);

        $user->notify(new InvoiceGeneratedNotification($invoice));

        return redirect()->back()->with('success', ucfirst($descType) . ' approved. Awaiting payment to be ready to publish.');
    }


    public function confirmPayment(Request $request, $type, $invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);

        Log::info('Confirm Payment', [
            'type' => $type,
            'invoiceId' => $invoiceId,
            'book_chapter_id' => $invoice->book_chapter_id,
            'invoice_status' => $invoice->status,
        ]);
        if ($invoice->status === 'paid') {
            return back()->with('error', 'Invoice is already paid.');
        }

        // Update invoice status
        $invoice->status = 'paid';
        $invoice->save();

        if ($type === 'paper') {
            $submission = ResearchPaper::find($invoice->research_paper_id);
            Log::info("Paper submission found:", optional($submission)->toArray());
        } else {
            $submission = BookChapter::find($invoice->book_chapter_id);
            Log::info("Chapter submission found:", optional($submission)->toArray());
        }

        if ($submission && $submission->status === 'pending_payment') {
            $submission->status = 'ready_to_publish';
            $submission->save();
            return redirect()->back()->with('success', ucfirst($type) . ' is now ready to publish.');
        }

        return back()->with('error', 'No matching ' . $type . ' found for this invoice or it is not in pending_payment status.');
    }
}
