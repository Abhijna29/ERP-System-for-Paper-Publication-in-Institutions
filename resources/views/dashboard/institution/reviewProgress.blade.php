@extends('layouts.institution')

@section('content')
<div class="container-fluid mt-5">
    <div class="card bg-white border-0 rounded-4 shadow">
        <div class="card-body user-card">
            <h5 class="card-title mb-3">{{ __('Review Progress Tracker') }}</h5>

            {{-- Filters (optional) --}}
            <form method="GET" action="{{ route('institution.reviews') }}" class="row mb-4 g-3 align-items-end">
                <div class="col-md-4">
                    <label for="department" class="form-label">{{ __('Filter by Department') }}</label>
                    <select class="form-select" name="department" id="department">
                        <option value="">{{ __('All Departments') }}</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="status" class="form-label">{{ __('Filter by Status') }}</label>
                    <select class="form-select" name="status" id="status">
                        <option value="">{{ __('All Statuses') }}</option>
                        @foreach (['submitted', 'under_review', 'resubmitted', 'revision_required', 'approved', 'rejected', 'published'] as $stat)
                            <option value="{{ $stat }}" {{ request('status') == $stat ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $stat)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">{{ __('Apply Filters') }}</button>
                </div>
            </form>

            {{-- Review Tracker Table --}}
            @if ($reviews->isEmpty()) 
                <p>{{ __('No papers reviewed under this institution') }}</p>
            @else
            <div class="table-responsive">
                <table class="table table-bordered border-dark-subtle table-hover">
                    <thead class="custom-header">
                        <tr>
                            <th>#</th>
                            <th>{{ __('Paper Title') }}</th>
                            <th>{{ __('Researcher') }}</th>
                            <th>{{ __('Department') }}</th>
                            <th>{{ __('Reviewer(s)') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Last Updated') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reviews as $index => $review)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                
                                <td>{{ optional($review->researchPaper)->title ?? 'N/A' }}</td>
                                <td>{{ $review->researchPaper->user->name }}</td>
                                <td>{{ optional($review->researchPaper->user->department)->name ?? 'N/A' }}</td>
                                <td>
                                    @forelse($review->researchPaper->reviewers as $reviewer)
                                        <span>{{ $reviewer->name }}</span>
                                    @empty
                                        <em>{{ __('No reviewers assigned') }}</em>
                                    @endforelse
                                </td>
                                <td>
                                    @php
                                        $badgeClass = match($review->status) {
                                            'approved' => 'text-success',
                                            'revision_required' => 'text-warning',
                                            'pending' => 'text-secondary',
                                            'rejected' => 'text-danger',
                                            'resubmitted' => 'text-info',
                                            default => 'text-info',
                                        };
                                    @endphp
                                    <span class=" {{ $badgeClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $review->status)) }}
                                    </span>
                                </td>
                                <td>{{ $review->updated_at->format('d M Y, H:i') }}</td>
                            </tr>                        
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection