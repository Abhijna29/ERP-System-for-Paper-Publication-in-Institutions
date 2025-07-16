@extends('layouts.admin')

@section('content')
<div class="card bg-white border-0 rounded-4 shadow">
    <div class="card-body user-card">
        <h5 class="card-title mb-3">{{ __('View Trademarks')}}</h5>
        @if(session('success')) 
            <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> 
            </div>
        @endif

         @if ($trademarks->isEmpty()) 
                {{ __('No trademarks filed') }}
            @else
            <div class="table-responsive">
                <table class="table table-bordered border-dark-subtle table-hover">
                    <thead class="custom-header">
                        <tr>
                            <th>{{ __('Title')}}</th>
                            <th>{{ __('Researcher')}}</th>
                            <th>{{ __('Status')}}</th>
                            <th>{{ __('Certificate')}}</th>
                            <th>{{ __('Action')}}</th>
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
                                    <a href="{{ asset('storage/' . $tm->certificate_path) }}" target="_blank" class="btn btn-primary btn-sm">{{ __('View')}}</a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.trademarks.updateStatus', $tm->id) }}">
                                    @csrf
                                    <select class="form-select w-75" name="status" onchange="this.form.submit()">
                                        <option value="filed" {{ $tm->status == 'filed' ? 'selected' : '' }}>Filed</option>
    <option value="registered" {{ $tm->status == 'registered' ? 'selected' : '' }}>Registered</option>
    <option value="rejected" {{ $tm->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
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
