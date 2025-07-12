@extends('layouts.researcher')

@section('content')
<div class="container-fluid mt-5">

    @if(session('submitted'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ __('Success!')}}</strong> {{ __('Your chapter has been submitted.')}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        
        </div>
    @endif

    <div class="card bg-white border-0 rounded-4 shadow">
        <div class="card-body user-card">
            <h5 class="card-title mb-3">{{ __('Chapter Submission Details')}}</h5>
            @if ($chapters->isEmpty()) 
                {{ __('No Chapters submitted')}}
            @else
            
            <div class="table-responsive">
                <table class="table table-bordered border-dark-subtle table-hover">
                    <thead class="custom-header">
                        <tr>
                            <th>{{ __('Chapter Title')}}</th>
                            <th>{{ __('Book Title')}}</th>
                            <th>{{ __('Genre')}}</th>
                            <th>{{ __('Review Comments')}}</th>
                            <th>{{ __('Status')}}</th>
                            <th>{{ __('Submitted On')}}</th>
                            <th>{{ __('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($chapters as $chapter)
                         {{-- @dd($chapter->reviews) --}}
                        <tr>                       
                        <td>{{ $chapter->chapter_title }}</td>
                        <td>{{ $chapter->book->title }}</td>
                        <td>{{ $chapter->genre}}</td>
                        <td>
                            @php
                                $reviews = $chapter->reviews;
                                // // Check if any review is still pending or resubmitted
                                // $isStillReviewing = $reviews->contains(fn($r) => in_array($r->status, ['pending', 'resubmitted']));

                                // $priorityStatus = ['revision_required', 'rejected', 'approved'];
                                // $relevantComment = null;

                                // if (!$isStillReviewing) {
                                //     foreach ($priorityStatus as $status) {
                                //         $filtered = $reviews->where('status', $status);
                                       
                                //             $relevantComment =  $filtered->comments;
                                            
                                //         }
                                //     }
                                // }
                            @endphp

                            {{-- @if ($relevantComment) --}}
                            @if ($reviews)
                                 @foreach ($reviews as $review)
                                    <li>{{ $review->comments }}</li>
                                @endforeach
                            @else
                                <em>{{ __('Pending')}}</em>
                            @endif
                        </td>  
                        <td>
                            @if($chapter->status == 'submitted')
                                <span class="text-dark">{{ __('Submitted')}}</span>
                            @elseif($chapter->status == 'under_review')
                                <span class=" text-dark">{{ __('Under Review')}} </span>
                            @elseif($chapter->status == 'resubmitted')
                                <span class=" text-dark">{{ __('Resubmitted')}} </span>
                            @elseif($chapter->status == 'approved')
                                <span class="text-success">{{ __('Approved')}}</span>
                            @elseif($chapter->status == 'rejected')
                                <span class="text-danger">{{ __('Rejected')}}</span>
                            @elseif($chapter->status == 'pending_payment')
                                <span class="text-danger">{{ __('Payment pending')}}</span>
                            @elseif($chapter->status == 'revision_required')
                                <span class="text-warning">{{ __('Revision required')}}</span>
                                <div class="mt-3">                                    
                                    {{-- @if($chapter->resubmission_count < 3) --}}
                                        <form action="{{ route('chapters.resubmit', $chapter->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <label for="resubmission_file">Upload Revised chapter (PDF only)</label>
                                            <input type="file" name="resubmission_file" required class="form-control my-2">
                                            <button type="submit" class="btn btn-primary">Resubmit</button>
                                        </form>
                                    {{-- @else
                                        <p class="text-danger">This chapter has reached the maximum number of resubmissions(3)</p>
                                    @endif --}}
                                </div>
                            @elseif($chapter->status == 'published')
                                <span class="text-success">{{ __('chapter Published')}}</span>
                            @endif

                        </td>
                        
                        
                        <td>{{ $chapter->created_at->format('d M Y, h:i A') }}</td>
                        <td>
                            @if($chapter->status === 'published')
                                <a href="{{ route('submission.download', ['type' => 'chapters', 'id' => $chapter->id]) }}" class="btn btn-success btn-sm">
                                    {{ __('Download Chapter')}}
                                </a>
                            @else
                                <a href="{{ asset('storage/' . $chapter->file_path) }}" target="_blank" class="btn btn-warning btn-sm">
                                    {{ __('View chapter')}}
                                </a>
                            @endif
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
