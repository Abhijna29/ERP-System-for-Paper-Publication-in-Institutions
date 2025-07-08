@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4">
    <div class="card bg-white border-0 rounded-4 shadow">
        <div class="card-body user-card">
            <h5 class="card-title mb-3 fw-bold">{{ __('Invoices')}}</h5>
            @if($invoices->count())
                <div class="table-responsive">
                    <table class="table table table-bordered border-dark-subtle table-hover fs-6" id="userTable">
                        <thead class="custom-header">
                            <tr>
                                <th>#</th>
                                <th>{{ __('Invoice')}} #</th>
                                <th>{{ __('User')}}</th>
                                <th>{{ __('Amount')}}</th>
                                <th>{{ __('Status')}}</th>
                                <th>{{ __('Invoice Date')}}</th>
                                <th>{{ __('Due Date')}}</th>
                                <th>{{ __('Actions')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $invoice)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $invoice->invoice_number }}</td>
                                <td>{{ $invoice->user->name }}</td>
                                <td>₹{{ number_format($invoice->amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $invoice->status == 'paid' ? 'success' : 'danger' }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.invoice.view', $invoice->id) }}" class="btn btn-sm btn-primary">{{ __('View')}}</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-info">{{ __('No invoices found.')}}</div>
            @endif
        </div>
    </div>
</div>

@endsection
