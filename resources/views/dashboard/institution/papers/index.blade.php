@extends('layouts.institution')

@section('content')
<div class="container-fluid mt-5">
    <div class="card bg-white border-0 rounded-4 shadow">
        <div class="card-body user-card">
            <h5 class="card-title mb-3">{{ __('Paper Submission Details')}}</h5>

            {{-- Filter Section --}}
            <form method="GET" action="{{ route('institution.submissions.index', ['type' => 'paper']) }}" class="row mb-4 g-3 align-items-end">
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

            @if ($submissions->isEmpty()) 
                <p>{{ __('No papers submitted under this institution') }}</p>
            @else
            <div class="table-responsive">
                <table class="table table-bordered border-dark-subtle table-hover">
                    <thead class="custom-header">
                        <tr>
                            <th>{{ __('Paper Title') }}</th>
                            <th>{{ __('Researcher') }}</th>
                            <th>{{ __('Department') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Submitted On') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($submissions as $paper)
                        <tr>
                            <td>{{ $paper->title }}</td>
                            <td>{{ $paper->researcher->name ?? 'N/A' }}</td>
                            <td>{{ $paper->researcher->department->name ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'submitted' => 'text-dark',
                                        'under_review' => 'text-primary',
                                        'resubmitted' => 'text-info',
                                        'revision_required' => 'text-warning',
                                        'approved' => 'text-success',
                                        'rejected' => 'text-danger',
                                        'published' => 'text-secondary',
                                    ];
                                @endphp
                                <span class="{{ $statusColors[$paper->status] ?? 'text-muted' }}">
                                    {{ ucfirst(str_replace('_', ' ', $paper->status)) }}
                                </span>
                            </td>
                            <td>{{ $paper->created_at->format('d M Y, h:i A') }}</td>
                            <td>
                                <a href="{{ route('institution.submissions.show',['type' => 'paper','id'=>$paper->id]) }}" class="btn btn-sm btn-primary">{{ __('View')}}</a>
                            </td>
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
