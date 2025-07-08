@extends('layouts.researcher')

@section('content')
<div class="container-fluid mt-4">
    <div class="card bg-white border-0 rounded-4 shadow">
        <div class="card-body user-card">
            <h5 class="card-title mb-3 fw-bold">{{ __('My Invoices & Payments')}}</h5>

            @if($invoices->count())
            <div class="table-responsive">
                <table class="table table table-bordered border-dark-subtle table-hover fs-6" id="userTable">
                    <thead class="custom-header">
                        <tr>
                            <th>#</th>
                            <th>{{ __('Invoice Number')}}</th>
                            <th>{{ __('Description')}}</th>
                            <th>{{ __('Amount')}}</th>
                            <th>{{ __('Status')}}</th>
                            <th>{{ __('Invoice Date')}}</th>
                            <th>{{ __('Due Date')}}</th>
                            <th>{{ __('Actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $index => $invoice)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $invoice->invoice_number }}</td>
                                <td>{{ $invoice->description }}</td>
                                <td>₹{{ number_format($invoice->amount, 2) }}</td>
                                <td>
                                    @if($invoice->status === 'paid')
                                        <span class="badge bg-success fs-6">{{ __('Paid')}}</span>
                                    @else
                                        <span class="badge bg-warning text-dark fs-6">{{ __('Unpaid')}}</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('researcher.invoice.view', $invoice->id) }}" class="btn btn-sm btn-info mb-2">{{ __('View')}}</a>
                                    @if($invoice->status === 'unpaid')
                                        <a href="{{ route('researcher.invoice.pay', $invoice->id) }}" class="btn btn-sm btn-primary p-1">{{ __('Pay Now')}}</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-info">No invoices found.</div>
            @endif
        </div>
    </div>
</div>
@endsection
