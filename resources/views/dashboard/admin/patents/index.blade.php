@extends('layouts.admin')

@section('content')
<div class="card bg-white border-0 rounded-4 shadow">
    <div class="card-body user-card">
        <h5 class="card-title mb-3">All Patents</h5>
        @if(session('success')) 
            <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if ($patents->isEmpty()) 
                {{ __('No patents filed') }}
            @else
            <div class="table-responsive">
                <table class="table table-bordered border-dark-subtle table-hover">
                    <thead class="custom-header">
                        <tr>
                            <th>Title</th>
                            <th>Inventor</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patents as $patent)
                            <tr>
                                <td>{{ $patent->work_title }}</td>
                                <td>{{ $patent->investors_name}}</td>
                                <td><span>{{ ucfirst($patent->type) }}</span></td>
                                <td>
                                    @if($patent->type === 'granted' && $patent->certificate_path)
                                        <a href="{{ asset('storage/' . $patent->certificate_path) }}" target="_blank" class="btn btn-sm btn-success">
                                        View Certificate
                                        </a>
                                    @else
                                        <a href="{{ route('admin.patents.edit', $patent->id) }}" class="btn btn-sm btn-warning">
                                            Edit
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
@endsection
