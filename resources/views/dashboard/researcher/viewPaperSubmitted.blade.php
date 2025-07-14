@extends('layouts.researcher')

@section('content')
<div class="container-fluid mt-5">

    @if(session('submitted'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ __('Success!')}}</strong> {{ __('Your paper has been submitted.')}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        
        </div>
    @endif

    <div class="card bg-white border-0 rounded-4 shadow g-4">
        <div class="card-body user-card">
            <h5 class="card-title mb-3">{{ __('Paper Submission Details')}}</h5>
            @if ($papers->isEmpty()) 
                {{ __('No papers submitted')}}
            @else
            
            <div class="table-responsive">
                <table class="table table-bordered border-dark-subtle table-hover">
                    <thead class="custom-header">
                        <tr>
                            <th>{{ __('Title')}}</th>
                            <th>{{ __('Status')}}</th>
                            <th>{{ __('Review Comments')}}</th>
                            <th>{{ __('Category')}}</th>
                            <th>{{ __('Sub Category')}}</th>
                            <th>{{ __('Child Category')}}</th>
                            <th>{{ __('Submitted On')}}</th>
                            <th>{{ __('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($papers as $paper)
                        <tr>                       
                        <td>{{ $paper->title }}</td>
                        <td>
                            @if($paper->status == 'submitted')
                                <span class="text-dark">{{ __('Submitted')}}</span>
                            @elseif($paper->status == 'under_review')
                                <span class=" text-dark">{{ __('Under Review')}} </span>
                            @elseif($paper->status == 'resubmitted')
                                <span class=" text-dark">{{ __('Resubmitted')}} </span>
                            @elseif($paper->status == 'approved')
                                <span class="text-success">{{ __('Approved')}}</span>
                            @elseif($paper->status == 'rejected')
                                <span class="text-danger">{{ __('Rejected')}}</span>
                            @elseif($paper->status == 'pending_payment')
                                <span class="text-danger">{{ __('Payment pending')}}</span>
                            @elseif($paper->status == 'ready_to_publish')
                                <span class="text-success">{{ __('Ready to publish')}}</span>
                            @elseif($paper->status == 'revision_required')
                                <span class="text-warning">{{ __('Revision required')}}</span>
                                <div class="mt-3">                                    
                                    {{-- @if($paper->resubmission_count < 3) --}}
                                        <form action="{{ route('papers.resubmit', $paper->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <label for="resubmission_file">{{ __('Upload Revised paper (PDF only)')}}</label>
                                            <input type="file" name="resubmission_file" required class="form-control my-2">
                                            <button type="submit" class="btn btn-primary">{{ __('Resubmit')}}</button>
                                        </form>
                                    {{-- @else
                                        <p class="text-danger">This paper has reached the maximum number of resubmissions(3)</p>
                                    @endif --}}
                                </div>
                            @elseif($paper->status == 'published')
                                <span class="text-success">{{ __('paper Published')}}</span>
                            @endif

                        </td>
                        <td>
                            @php
                                $reviews = $paper->reviews;

                                // // Check if any review is still pending or resubmitted
                                // $isStillReviewing = $reviews->contains(fn($r) => in_array($r->status, ['pending', 'resubmitted']));

                                // $priorityStatus = ['revision_required', 'rejected', 'approved'];
                                // $relevantComment = null;              
                                // if (!$isStillReviewing) {
                                //     foreach ($priorityStatus as $status) {
                                //         $filtered = $reviews->where('status', $status)->sortByDesc(fn($r) => strlen($r->comments));
                                //         if ($filtered->isNotEmpty()) {
                                //             $relevantComment =  $filtered->first()->comments;
                                //             break;
                                //         }
                                //     }
                                // }
                            @endphp

                           @if ($reviews->isEmpty())
                                <em>{{ __('Pending') }}</em>
                            @else
                                @foreach ($reviews as $review)
                                    {{ $review->comments }}
                                @endforeach
                            @endif

                        </td>
                        <td>{{ $paper->category->name }}</td>
                        <td>{{ $paper->subCategory->name ?? "N/A"}}</td>
                        <td>{{ $paper->childCategory->name }}</td>
                        <td>{{ $paper->created_at->format('d M Y, h:i A') }}</td>
                        <td>
                            @if($paper->status === 'published')
                               <a href="{{ route('submission.download', ['type' => 'papers', 'id' => $paper->id]) }}" class="btn btn-success btn-sm">
                            {{ __('Download Paper')}}
                        </a>
                            @else
                                <a href="{{ asset('storage/' . $paper->file_path) }}" target="_blank" class="btn btn-warning btn-sm">
                                    {{ __('View paper')}}
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
