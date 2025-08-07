<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class AdminInvoiceController extends Controller
{
    public function create()
    {
        return view('dashboard.admin.invoices.invoice');
    }

    public function listInvoices()
    {
        $invoices = Invoice::with('user')->oldest()->get();
        return view('dashboard.admin.invoices.index', compact('invoices'));
    }

    public function viewInvoice($id)
    {
        $invoice = Invoice::with('user')->findOrFail($id);
        return view('dashboard.admin.invoices.view', compact('invoice'));
    }
    public function markAsPaid($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->status = 'paid';
        $invoice->save();

        return redirect()->route('admin.invoice.view', $invoice->id)
            ->with('success', 'Invoice marked as paid.');
    }
}
