@extends('layouts.department')

@section('content')
<div class="container-fluid mt-5">
    <div class="card bg-white border-0 rounded-4 shadow">
        <div class="card-body user-card">
            <h5 class="card-title mb-3">{{ __('Chapter Submission Details')}}</h5>

            {{-- Filter Section --}}
            <form method="GET" action="{{ route('department.submissions.index', ['type' => 'chapter']) }}" class="row mb-4 g-3 align-items-end">
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
                <p>{{ __('No chapters submitted under this department') }}</p>
            @else
            <div class="table-responsive">
                <table class="table table-bordered border-dark-subtle table-hover">
                    <thead class="custom-header">
                        <tr>
                            <th>{{ __('Chapter Title') }}</th>
                            <th>{{ __('Researcher') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Submitted On') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($submissions as $chapter)
                        <tr>
                            <td>{{ $chapter->chapter_title }}</td>
                            <td>{{ $chapter->user->name ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'submitted' => 'text-dark',
                                        'under_review' => 'text-primary',
                                        'resubmitted' => 'text-body-emphasis',
                                        'revision_required' => 'text-warning',
                                        'approved' => 'text-info',
                                        'rejected' => 'text-danger',
                                        'ready_to_publish' => 'text-success-emphasis',
                                        'published' => 'text-success',
                                    ];
                                @endphp
                                <span class="{{ $statusColors[$chapter->status] ?? 'text-muted' }}">
                                    {{ ucfirst(str_replace('_', ' ', $chapter->status)) }}
                                </span>
                            </td>
                            <td>{{ $chapter->created_at->format('d M Y, h:i A') }}</td>
                            <td>
                                <a href="{{ route('department.submissions.show',['type' => 'chapter','id'=>$chapter->id]) }}" class="btn btn-sm btn-primary">{{ __('View')}}</a>
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
