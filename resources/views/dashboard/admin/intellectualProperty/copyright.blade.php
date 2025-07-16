@extends('layouts.admin')
@section('content')
<div class="card bg-white border-0 rounded-4 shadow">
    <div class="card-body user-card">
        <h5 class="card-title mb-3">{{ __('View Copyrights')}}</h5>
        @if(session('success')) 
            <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> 
            </div>
        @endif

         @if ($copyrights->isEmpty()) 
            {{ __('No copyrights filed') }}
        @else
        <div class="table-responsive">
            <table class="table table-bordered border-dark-subtle table-hover">
                <thead class="custom-header">
                    <tr>
                        <th>{{ __('Researcher')}}</th>
                        <th>{{ __('Title')}}</th>
                        <th>{{ __('Type')}}</th>
                        <th>{{ __('Status')}}</th>
                        <th>{{ __('Actions')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($copyrights as $c)
                    <tr>
                        <td>{{ $c->user->name }} ({{ $c->user->email }})</td>
                        <td>{{ $c->title }}</td>
                        <td>{{ ucfirst($c->type_of_work) }}</td>
                        <td>{{ ucfirst($c->status) }}</td>
                        <td>
                            @if($c->certificate_path)
                                <a href="{{ asset('storage/' . $c->certificate_path) }}" target="_blank" class="btn btn-primary btn-sm">{{ __('View Certificate')}}</a>
                                @if($c->status != 'registered')
                                    <form action="{{ route('admin.copyrights.update', $c->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    <input type="hidden" name="status" value="registered">
                                    <button type="submit" class="btn btn-sm btn-success">{{ __('Mark as Registered')}}</button>
                                    </form>
                                @endif
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