@extends('layouts.reviewer')

@section('content')
<div class="container-fluid">
    <div class="card bg-white border-0 rounded-4 shadow">
        <div class="card-body user-card">
            <h5 class="card-title mb-3">
                {{ __('Submit Review for') }} 
                "@if($type === 'paper')
                    {{ $review->researchPaper->title ?? 'N/A' }}
                @else
                    {{ $review->bookChapter->chapter_title ?? 'N/A' }}
                @endif"
            </h5>

           <form method="POST" action="{{ route('reviewer.review.submit', ['type' => $type, 'id' => $type === 'paper' ? $review->research_paper_id : $review->book_chapter_id]) }}" id="reviewerForm">
                @csrf

                <div class="form-group mb-3">
                    <label for="comments">{{ __('Review Comments:')}}</label>
                    <textarea name="comments" id="comments" class="form-control" rows="4" required>{{ old('comments', $review->comments) }}</textarea>
                </div>

                <div class="form-group mb-3">
                    <label for="rating">{{ __('Rating (1-5):')}}</label>
                    <input type="number" name="rating" id="rating" class="form-control" value="{{ old('rating', $review->rating) }}" min="1" max="5">
                </div>

                <div class="form-group mb-3">
                    <label for="status">{{ __('Status:')}}</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="pending" {{ $review->status == 'pending' ? 'selected' : '' }}>{{ __('Pending')}}</option>
                        <option value="approved" {{ $review->status == 'approved' ? 'selected' : '' }}>{{ __('Approved')}}</option>
                        <option value="rejected" {{ $review->status == 'rejected' ? 'selected' : '' }}>{{ __('Rejected')}}</option>
                        <option value="revision_required" {{ $review->status == 'revision_required' ? 'selected' : '' }}>{{ __('Revision Required')}}</option>
                        
                    </select>
                </div>   

                <div class="form-group mb-3">
                    <label>
                        {{ $type === 'paper' ? __('Research Paper:') : __('Book Chapter:') }}
                    </label><br>

                    @if($type === 'paper' && $review->researchPaper)
                        <iframe src="{{ asset('storage/' . $review->researchPaper->file_path) }}" width="100%" height="600px" style="border: 1px solid #ccc;"></iframe>
                    @elseif($type === 'chapter' && $review->bookChapter)
                        <iframe src="{{ asset('storage/' . $review->bookChapter->file_path) }}" width="100%" height="600px" style="border: 1px solid #ccc;"></iframe>
                    @else
                        <em>No file available</em>
                    @endif
                </div>

                <div class="form-group mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="flagged_for_editor" id="flagged_for_editor" value="1"
                            {{ old('flagged_for_editor') ? 'checked' : '' }}>
                        <label class="form-check-label" for="flagged_for_editor">
                            Flag this paper for editor attention
                        </label>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-success">{{ __('Submit Review')}}</button>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- @push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const reviewerForm = document.getElementById("reviewerForm");
    
    reviewerForm.addEventListener("submit", async function (e) {
        e.preventDefault();

        const comments = document.getElementById("comments").value;
        const rating = document.getElementById("rating").value;
        const status = document.getElementById("status").value;

        // Client-side validation
        let hasError = false;
        const fields = ["comments", "rating", "status"];

        fields.forEach((id) => {
            const input = document.getElementById(id);
            const error = document.getElementById(`error-${id}`);
            input.addEventListener("input", () => {
                if (input.value.trim()) {
                    error.textContent = "";
                }
            });
            if (!input.value.trim()) {
                error.textContent = `Please enter the ${id.replace('_', ' ')}`;
                hasError = true;
            } else {
                error.textContent = "";
            }
        });

        if (hasError) return;
    });
});
        
</script>
@endpush --}}