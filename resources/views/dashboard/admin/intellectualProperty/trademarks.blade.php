@extends('layouts.admin')

@section('content')
<div class="card bg-white border-0 rounded-4 shadow">
    <div class="card-body user-card">
        <h5 class="card-title mb-3">All Trademarks</h5>
        @if(session('success')) 
            <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> 
        @endif

         @if ($trademarks->isEmpty()) 
                {{ __('No trademarks filed') }}
            @else
            <div class="table-responsive">
                <table class="table table-bordered border-dark-subtle table-hover">
                    <thead class="custom-header">
                        <tr>
                            <th>Title</th>
                            <th>Researcher</th>
                            <th>Status</th>
                            <th>Certificate</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trademarks as $tm)
                        <tr>
                            <td>{{ $tm->title }}</td>
                            <td>{{ $tm->user->name }}</td>
                            <td>{{ ucfirst($tm->status) }}</td>
                            <td>
                                @if($tm->certificate_path)
                                    <a href="{{ asset('storage/' . $tm->certificate_path) }}" target="_blank" class="btn btn-primary btn-sm">View</a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.trademarks.updateStatus', $tm->id) }}">
                                    @csrf
                                    <select class="form-select w-75" name="status" onchange="this.form.submit()">
                                        <option {{ $tm->status == 'filed' ? 'selected' : '' }}>Filed</option>
                                        <option {{ $tm->status == 'registered' ? 'selected' : '' }}>Registered</option>
                                        <option {{ $tm->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
