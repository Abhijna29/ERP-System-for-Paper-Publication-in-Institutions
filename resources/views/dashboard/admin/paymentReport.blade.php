@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4">
    <div class="card bg-white border-0 rounded-4 shadow">
        <div class="card-body user-card">
            <h5 class="card-title mb-3 fw-bold">{{ __('Payment Report')}}</h5>
            <form method="GET" action="{{ route('admin.paymentReport') }}" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <input type="date" name="from" class="form-control" value="{{ request('date') }}">
                    </div>
                    {{-- <div class="col-md-3">
                        <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                    </div> --}}
                    <div class="col-md-3">
                        <button class="btn btn-primary" type="submit">{{ __('FILTER')}}</button>
                    </div>
                </div>
            </form>

            @if($payments->count())
                <div class="table-responsive">
                    <table class="table table table-bordered border-dark-subtle table-hover fs-6" id="userTable">
                        <thead class="custom-header">
                            <tr>
                                <th>{{ __('Payment ID')}}</th>
                                <th>{{ __('User')}}</th>
                                <th>{{ __('Invoice')}} #</th>
                                <th>{{ __('Amount')}}</th>
                                <th>{{ __('Status')}}</th>
                                <th>{{ __('Paid On')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                            <tr>
                                <td>{{  $loop->iteration }}</td>
                                <td>{{ $payment->user->name }}</td>
                                <td>{{ $payment->invoice->invoice_number }}</td>
                                <td>₹{{ number_format($payment->amount, 2) }}</td>
                                <td><span class="badge bg-success">{{ ucfirst($payment->status) }}</span></td>
                                <td>{{ $payment->created_at->format('d M Y') }}</td>
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
