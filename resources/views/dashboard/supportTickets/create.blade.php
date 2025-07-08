@php
    // Map roles to layouts (adjust paths as per your project)
    $layouts = [
        'admin' => 'layouts.admin',
        'researcher' => 'layouts.researcher',
        'reviewer' => 'layouts.reviewer',
        'institution' => 'layouts.institution',
        'department' => 'layouts.department',
    ];

    // Pick layout or default to researcher layout
    $layout = $layouts[$role];
@endphp

@extends($layout)
@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
<div class="container py-4">
    <div class="card bg-white border-0 rounded-4 shadow">
        <div class="card-body user-card">
            <h5 class="card-title mb-4">{{ __('Submit Support Ticket')}}</h5>

            <form method="POST" action="{{ route('supportTickets.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="issue_description" class="form-label">{{ __('Describe your issue')}}</label>
                    <textarea class="form-control" id="issue_description" name="issue_description" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('Submit Ticket')}}</button>
            </form>
        </div>
    </div>
</div>
@endsection
