@extends('layouts.researcher')

@section('content')
<div class="container-fluid mt-4">
    <div class="card bg-white border-0 rounded-4 shadow mb-3">
        <div class="card-body user-card">
            <h5 class="card-title mb-3 fw-bold">{{ __('Pay Invoice')}}</h5>

            <p><strong>{{ __('Invoice #')}}: </strong>{{ $invoice->invoice_number }}</p>
            <p><strong>{{ __('Description')}}:</strong> {{ $invoice->description }}</p>
            <p><strong>{{ __('Amount')}}:</strong> ₹{{ number_format($invoice->amount, 2) }}</p>
            <p><strong>{{ __('Due Date')}}:</strong> {{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</p>

            <form action="{{ route('researcher.invoice.razorpay.success', $invoice->id) }}" method="POST">
    @csrf
    <script
        src="https://checkout.razorpay.com/v1/checkout.js"
        data-key="{{ config('services.razorpay.key') }}"
        data-amount="{{ $invoice->amount * 100 }}"
        data-currency="INR"
        data-order_id="{{ $order_id }}"
        data-buttontext="Pay ₹{{ number_format($invoice->amount, 2) }}"
        data-name="Journal Publication"
        data-description="{{ $invoice->description }}"
        data-prefill.name="{{ Auth::user()->name }}"
        data-prefill.email="{{ Auth::user()->email }}"
        data-theme.color="#0d6efd">
    </script>
</form>

        </div>
    </div>

    <a href="{{ route('researcher.invoices') }}" class="btn btn-secondary mx-2">{{ __('Back to Invoices')}}</a>
</div>
@endsection

