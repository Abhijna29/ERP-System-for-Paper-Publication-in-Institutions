@extends('layouts.admin')

@section('content')
<div class="card bg-white border-0 rounded-4 shadow">
    <div class="card-body user-card">
        <h5 class="card-title mb-3">{{ __('View Design Rights')}}</h5>
        @if($designs->isEmpty())
            {{ __('No designs filed') }}
            @else
            @foreach($designs as $design)
                <div class="mb-2">
                    <strong>{{ $design->title }}</strong> by {{ $design->user->name }}
                </div>
                <div class="mb-2">{{ __('Status')}}: <span>{{ ucfirst($design->status) }}</span></div>

                <form method="POST" action="{{ route('designs.updateStatus', $design->id) }}" class="d-inline">
                    @csrf
                    <select class="form-select w-25" name="status" onchange="this.form.submit()">
                        <option value="">{{ __('Change Status')}}</option>
                        <option value="under_review">{{ __('Under Review')}}</option>
                        <option value="approved">{{ __('Approved')}}</option>
                        <option value="rejected">{{ __('Rejected')}}</option>
                    </select>
                </form>
                <div class="mt-3">
                    @if($design->design_file_path)
                        <a href="{{ asset('storage/' . $design->design_file_path) }}" target="_blank" class="btn btn-primary">{{ __('View Design File')}}</a>
                    @endif
                    @if($design->certificate_path)
                        <a href="{{ asset('storage/' . $design->certificate_path) }}" target="_blank" class="btn btn-success">{{ __('View Design Certificate')}}</a>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
