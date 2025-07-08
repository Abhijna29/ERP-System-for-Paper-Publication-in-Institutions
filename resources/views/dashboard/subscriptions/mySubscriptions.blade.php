@extends('layouts.institution')

@section('content')
<div class="container py-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
     <div class="card bg-white border-0 rounded-4 shadow">
        <div class="card-body user-card">
            <h5 class="card-title mb-4">{{ __('My Subscriptions')}}</h5>
            @if ($subscriptions->isEmpty()) 
            {{ __('You have not subscribed to any plans.')}}
            @else
            <div class="table-responsive">
                <table class="table table-bordered border-dark-subtle table-hover">
                    <thead class="custom-header">
                    <tr>
                        <th>{{ __('Plan Name')}}</th>
                        <th>{{ __('Duration')}}</th>
                        <th>{{ __('Starts At')}}</th>
                        <th>{{ __('Ends At')}}</th>
                        <th>{{ __('Paper Usage')}}</td>
                        <th>{{ __('Download Usage')}}</td>
                        <th>{{ __('Status')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscriptions as $sub)
                        @if (
                            ($role === 'institution' && $sub->plan->paper_limit > 0) ||
                            ($role !== 'institution' && $sub->plan->paper_limit == 0)
                        )
                            <tr>
                                <td>{{ $sub->plan->name }}</td>
                                <td>{{ $sub->plan->duration }}</td>
                                <td>{{ \Carbon\Carbon::parse($sub->starts_at)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($sub->ends_at)->format('d M Y') }}</td>
                                <td>{{ $sub->papers_used }} / {{ $sub->plan->paper_limit }}</td>
                                <td>{{ $sub->downloads_used }} / {{ $sub->plan->download_limit }}</td>
                                <td>
                                    @if (now()->between($sub->starts_at, $sub->ends_at))
                                        <span class="badge bg-success">{{ __('Active')}}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('Expired')}}</span>
                                    @endif
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
</div>
@endsection
