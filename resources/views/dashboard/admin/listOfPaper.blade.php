@extends('layouts.admin')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card">
                <h5 class="card-title mb-3 fw-bold">{{ __('List of Paper Published') }}</h5>
                @if ($papers->isEmpty()) 
                {{ __('No papers published') }}
                @else
                <div class="table-responsive">
                    <table class="table table-bordered border-dark-subtle table-hover fs-6">
                        <thead class="custom-header">
                            <tr>
                                <th>{{ __('Id') }}</th>
                                <th>{{ __('Title of the Paper') }}</th>
                                <th>{{ __('Author') }}</th>
                                <th>{{ __('Published On') }}</th>
                                <th>{{ __('Indexed Database') }}</th>
                                <th>{{ __('Journal') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($papers as $paper)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $paper->title }}</td>
                                <td>{{ $paper->all_authors }}</td>
                                <td>{{ $paper->publication_date }}</td>
                                <td>{{ ucfirst($paper->indexing_database) }}</td>
                                <td>{{ $paper->source ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('admin.paper.view', $paper->id) }}" class="btn btn-warning btn-sm mb-2">{{ __('View Paper') }}</a>
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
</div>
@endsection