@extends('layouts.admin')

@section('content')
<div class="container">
    <h3 class="mb-4">
        {{ __('Assign Reviewers for :title', ['title' => $submission->title ?? $submission->chapter_title]) }}
    </h3>   

    <form id="assignForm" method="POST" action="{{ $type === 'paper' 
        ? route('admin.assign.submit', $submission->id) 
        : route('admin.chapter.assign.submit', $submission->id) }}">
        @csrf

        <div class="card bg-white rounded-4 border-0">
            <div class="card-body">
                <h5 class="card-title">{{ __('Select Reviewers:') }}</h5>

                @foreach($reviewers as $reviewer)
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="reviewers[]" 
                               value="{{ $reviewer->id }}" 
                               id="rev{{ $reviewer->id }}"
                               @if(in_array($reviewer->id, $assignedReviewers)) checked @endif
                               @if(count($assignedReviewers) >= 3 && !in_array($reviewer->id, $assignedReviewers)) disabled @endif>

                        <label class="form-check-label" for="rev{{ $reviewer->id }}">
                           {{ $reviewer->name }} <small class="text-muted">({{ __('Total number of Assignment')}}: {{ $reviewer->total_active_reviews ??0 }})</small>
                        </label>
                    </div>
                    <div class="mb-4">
                        <label for="deadlines"> {{ __('Review Deadline')}}:</label>
                        <input type="date" name="deadlines[{{ $reviewer->id }}]" class="form-control w-25"
                        value="{{ optional($submission->reviews->where('reviewer_id', $reviewer->id)->first())->deadline }}">
                    </div>
                @endforeach

                @if(count($assignedReviewers) >= 3)
                    <div class="alert alert-warning mt-3">{{ __('You can only assign up to 3 reviewers.') }}</div>
                @endif
            </div>
        </div>

        <div id="formErrorMessage" class="text-danger fw-semibold mb-2" style="display:none;"></div>
        <button type="submit" class="btn btn-success mt-3">
            {{ __('Assign Reviewers') }}
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('assignForm');
        const errorDiv = document.getElementById('formErrorMessage');

        form.addEventListener('submit', function (e) {
            const checkedReviewers = document.querySelectorAll('input[name="reviewers[]"]:checked');
            let valid = true;
            let message = '';

            // Reset previous highlights
            document.querySelectorAll('input[type="date"]').forEach(input => {
                input.classList.remove('border', 'border-danger');
            });

            if (checkedReviewers.length === 0) {
                valid = false;
                message = 'Please select at least one reviewer.';
            } else {
                checkedReviewers.forEach(input => {
                    const reviewerId = input.value;
                    const deadlineInput = document.querySelector(`input[name="deadlines[${reviewerId}]"]`);
                    if (!deadlineInput || !deadlineInput.value) {
                        valid = false;
                        message = 'Please set a deadline for all selected reviewers.';
                        deadlineInput.classList.add('border', 'border-danger'); // highlight the empty one
                    }
                });
            }

            if (!valid) {
                e.preventDefault();
                errorDiv.textContent = message;
                errorDiv.style.display = 'block';
            } else {
                errorDiv.style.display = 'none';
            }
        });
    });
</script>
@endpush

