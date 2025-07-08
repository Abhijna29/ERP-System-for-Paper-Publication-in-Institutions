@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card bg-white border-0 rounded-4 shadow">
        <div class="card-body user-card">
            <h5 class="card-title mb-3">{{ __('List of Submitted Papers') }}</h5>

            @if ($papers->isEmpty()) 
                {{ __('No papers submitted') }}
            @else
            <div class="table-responsive">
                <table class="table table-bordered border-dark-subtle table-hover">
                    <thead class="custom-header">
                        <tr>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Researcher') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Review Comments') }}</th>
                            <th>{{ __('Assigned Reviewers') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($papers as $paper)
                        @php
                            $flaggedReview = $paper->reviews->where('flagged_for_editor', true)->sortByDesc('created_at')->first();
                        @endphp
                        <tr>
                            <td>
                                {{ $paper->title }}
                                
                                @if($flaggedReview)
                                    <div class="alert alert-danger mt-2 mb-0 p-2">
                                        <strong>{{ __('⚠️ Flagged:')}}</strong> {{ __('Needs editorial review.')}}
                                    </div>
                                    <form method="POST" action="{{ route('admin.flag.resolve', $flaggedReview->id) }}" class="mt-3">
                                        @csrf
                                        <div class="d-flex gap-2">
                                            <button name="action" value="resolve" class="btn btn-outline-secondary">{{ __('Mark as Resolved')}}</button>
                                            <button name="action" value="request_revision" class="btn btn-warning">{{ __('Request Revision')}}</button>
                                            <button name="action" value="reject" class="btn btn-danger">{{ __('Reject Paper')}}</button>
                                        </div>
                                    </form>
                                @endif
                            </td>
                            <td>{{ $paper->user->name }}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $paper->status)) }}</td>
                            <td>
                            @php
                                $reviews = $paper->reviews;
                                // Check if any review is still pending or resubmitted
                                $isStillReviewing = $reviews->contains(fn($r) => in_array($r->status, ['pending', 'resubmitted']));

                                $priorityStatus = ['revision_required', 'rejected', 'approved'];
                                $relevantComment = null;

                                if (!$isStillReviewing) {
                                    foreach ($priorityStatus as $status) {
                                        $filtered = $reviews->where('status', $status)->sortByDesc(fn($r) => strlen($r->comments));
                                        if ($filtered->isNotEmpty()) {
                                            $relevantComment =  $filtered->first()->comments;
                                            break;
                                        }
                                    }
                                }
                            @endphp

                            @if ($relevantComment)
                                {{ $relevantComment }}
                            @else
                                <em>{{ __('Pending')}}</em>
                            @endif
                        </td>
                            <td>
                                @forelse ($paper->reviews as $review)
                                    <div>{{ $review->reviewer->name }}</div>
                                @empty
                                    <em>{{ __('No reviewers assigned') }}</em>
                                @endforelse
                            </td>
                            <td>
                                <a href="{{ route('admin.paper.view', $paper->id) }}" class="btn btn-warning btn-sm mb-2" >{{ __('View Paper')}}</a>
                                @if($paper->status !== 'pending_payment' && $paper->status !== 'ready_to_publish')
                                    <a href="{{ route('admin.assign.submit', $paper->id) }}" class="btn btn-sm btn-primary mb-2">{{ __('Assign Reviewer') }}</a>
                                @endif

                                @if($paper->status === 'approved')
                                    <button type="button" class="btn btn-success btn-sm approve-btn" data-id="{{ $paper->id }}">
                                        {{ __('Final Approve') }}
                                    </button>
                                    <form id="approve-form-{{ $paper->id }}" action="{{ route('admin.approve.submission', ['type' => 'paper', 'id' => $paper->id]) }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                @endif
                                @if($paper->status === 'pending_payment')
                                    <div class="alert alert-info p-2 mb-2">
                                        {{ __('Awaiting Payment') }}
                                    </div>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const approveButtons = document.querySelectorAll('.approve-btn');

        approveButtons.forEach(button => {
            button.addEventListener('click', function () {
                const paperId = this.dataset.id;

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will approve the paper and generate an invoice! Status will update to 'Ready to Publish' after payment.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, approve it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Hide the approve button
                        button.style.display = 'none';

                        // Also hide the "Assign" button if present in the same cell
                        const td = button.closest('td');
                        const assignButton = td.querySelector('.btn-primary');
                        if (assignButton) {
                            assignButton.style.display = 'none';
                        }

                        // Submit the form
                        document.getElementById('approve-form-' + paperId).submit();
                    }
                });
            });
        });

        const journalSelects = document.querySelectorAll('.journal-select');
        journalSelects.forEach(select => {
            select.addEventListener('change', function () {
                const url = this.value;
                if (url) {
                    window.location.href = url;
                }
            });
        });
    });
</script>
@endpush