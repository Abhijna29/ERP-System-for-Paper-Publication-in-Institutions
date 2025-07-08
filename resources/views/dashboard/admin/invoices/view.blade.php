@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4">
    <div class="card bg-white border-0 rounded-4 shadow mb-3">
        <div class="card-body user-card">
            <h5 class="card-title mb-3 fw-bold">{{ __('Invoice Details')}}</h5>

            <p><strong>{{ __('Invoice')}} #: {{ $invoice->invoice_number }}</strong></p>
            <p><strong>{{ __('User')}}:</strong> {{ $invoice->user->name }}</p>
            <p><strong>{{ __('Description')}}:</strong> {{ $invoice->description }}</p>
            <p><strong>{{ __('Amount')}}:</strong> ₹{{ number_format($invoice->amount, 2) }}</p>
            <p><strong>{{ __('Status')}}:</strong> 
                <span class="badge bg-{{ $invoice->status == 'paid' ? 'success' : 'warning text-dark' }}">
                    {{ ucfirst($invoice->status) }}
                </span>
            </p>
            <p><strong>{{ __('Invoice Date')}}:</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</p>
            <p><strong>{{ __('Due Date')}}:</strong> {{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</p>
        </div>
    </div>

    {{-- @if($invoice->status === 'unpaid')
    <form method="POST" action="{{ route('admin.invoice.markPaid', $invoice->id) }}" class="mt-3">
        @csrf
        @method('PATCH')
        <button type="submit" class="btn btn-success mx-2 mb-2">{{ __('Mark as Paid')}}</button>
    </form>
    @endif --}}

    <a href="{{ route('admin.invoices') }}" class="btn btn-secondary mx-2">{{ __('Back to Invoices')}}</a>
</div>
@endsection
