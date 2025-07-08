@extends('layouts.researcher')

@section('content')
<div class="container-fluid mt-4">
    <div class="card bg-white border-0 rounded-4 shadow mb-3">
        <div class="card-body user-card">
            <h5 class="card-title mb-3 fw-bold">{{ __('Invoice Details')}}</h5>

            <p><strong>{{ __('Invoice #')}}:</strong> {{ $invoice->invoice_number }}</p>
            <p><strong>{{ __('Description')}}:</strong> {{ $invoice->description }}</p>
            <p><strong>{{ __('Amount')}}:</strong> ₹{{ number_format($invoice->amount, 2) }}</p>
            <p><strong>{{ __('Status')}}:</strong>
                @if($invoice->status === 'paid')
                    <span class="badge bg-success">{{ __('Paid')}}</span>
                @else
                    <span class="badge bg-warning text-dark">{{ __('Unpaid')}}</span>
                @endif
            </p>
            <p><strong>{{ __('Invoice Date')}}:</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</p>
            <p><strong>{{ __('Due Date')}}:</strong> {{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</p>
        </div>
    </div>

    @if($invoice->status === 'unpaid')
        <a href="{{ route('researcher.invoice.pay', $invoice->id) }}" class="btn btn-primary mx-2">{{ __('Proceed to Payment')}}</a>
    @endif

    <a href="{{ route('researcher.invoices') }}" class="btn btn-secondary mx-2">{{ __('Back to Invoices')}}</a>
</div>
@endsection
