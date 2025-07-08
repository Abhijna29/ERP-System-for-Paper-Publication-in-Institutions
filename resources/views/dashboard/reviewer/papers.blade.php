@extends('layouts.reviewer')

@section('content') 
<div class="container-fluid mt-5">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif


    <div class="card bg-white border-0 rounded-4 shadow">
        <div class="card-body user-card">
            <h5 class="card-title mb-4">{{ __('Assigned Papers')}}</h5>
            @if ($reviews->isEmpty()) 
            {{ __('No papers assigned')}}
            @else
            <div class="table-responsive">
                <table class="table table-bordered border-dark-subtle table-hover">
                    <thead class="custom-header">
                        <tr>
                            <th>{{ __('Paper Title')}}</th>
                            <th>{{ __('Action')}}</th>
                            <th>{{ __('Status')}}</th>
                            <th>{{ __('Resubmission Count')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reviews as $review)
                            <tr>
                                <td>{{ $review->researchPaper->title }}</td>
                                <td>
                                    @if($review->comments)
                                        <span class="text-success">Submitted</span>
                                    @else
                                        <a href="{{ route('reviewer.reviewForm', ['type' => 'paper', 'id' => $review->research_paper_id]) }}" class="btn btn-sm btn-primary">{{ __('Submit Review')}}</a>
                                    @endif
                                </td>
                                <td>
                                    {{ ucfirst($review->status) }}
                                </td>
                                <td>
                                    {{ $review->researchPaper->resubmission_count }}
                                    {{-- @if ($review->researchPaper->resubmission_count >= 3)
                                        <div class="alert alert-warning px-1 py-2">
                                            {{ __('This paper has reached the maximum number of resubmissions.')}}
                                        </div>
                                    @endif --}}
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
