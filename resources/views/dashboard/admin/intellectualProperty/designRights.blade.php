@extends('layouts.admin')

@section('content')
<div class="card bg-white border-0 rounded-4 shadow">
    <div class="card-body user-card">
        <h5 class="card-title mb-3">All Design Submissions</h5>
            @foreach($designs as $design)
            <div class="mb-2">
                <strong>{{ $design->title }}</strong> by {{ $design->user->name }}
            </div>
            <div class="mb-2">Status: <span>{{ ucfirst($design->status) }}</span></div>

            <form method="POST" action="{{ route('designs.updateStatus', $design->id) }}" class="d-inline">
                @csrf
                <select class="form-select w-25" name="status" onchange="this.form.submit()">
                    <option value="">Change Status</option>
                    <option value="under_review">Under Review</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </form>
            <div class="mt-3">
                @if($design->design_file_path)
                    <a href="{{ asset('storage/' . $design->design_file_path) }}" target="_blank" class="btn btn-primary">View Design File</a>
                @endif
                @if($design->certificate_path)
                    <a href="{{ asset('storage/' . $design->certificate_path) }}" target="_blank" class="btn btn-success">View Design Certificate</a>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection
