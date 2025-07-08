@extends('layouts.admin') {{-- Your admin layout file --}}

@section('content')
<div class="container mt-4">
    <h2>Review Paper Submission</h2>

    <div class="card mb-4">
        <div class="card-header">Paper Details</div>
        <div class="card-body">
            <h4>{{ $paper->title }}</h4>
            <p><strong>Author:</strong> {{ $paper->user->name }}</p>
            <p><strong>Abstract:</strong> {{ $paper->abstract }}</p>
            <p><strong>Keywords:</strong> {{ $paper->keywords }}</p>
            <p><strong>Current Status:</strong> <span class="badge bg-info">{{ $paper->status }}</span></p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.papers.updateStatus', $paper->id) }}">
        @csrf
        <div class="mb-3">
            <label for="status" class="form-label">Update Paper Status:</label>
           <select name="status" id="status" class="form-select" required>
                <option value="">-- Choose Status --</option>
                <option value="under_review">✅ Ready for Review</option>
                <option value="revision_required">🔁 Needs Revision</option>
                <option value="rejected">❌ Rejected</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Status</button>
    </form>

    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif
</div>
@endsection
