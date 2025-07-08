<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminPaymentReportController extends Controller
{
    public function paymentReport(Request $request)
    {
        $query = Payment::with(['user', 'invoice']);

        if ($request->from) {
            $query->whereDate('created_at', $request->from);
        }

        // if ($request->to) {
        //     $query->whereDate('created_at', '<=', $request->to);
        // }

        $payments = $query->oldest()->get();
        return view('dashboard.admin.paymentReport', compact('payments'));
    }
}
