@extends('layouts.admin')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card">
                <h5 class="card-title mb-3 fw-bold">{{ __('List of Book Chapters Published') }}</h5>
                @if ($chapters->isEmpty()) 
                {{ __('No chapters published') }}
                @else
                <div class="table-responsive">
                    <table class="table table-bordered border-dark-subtle table-hover fs-6">
                        <thead class="custom-header">
                            <tr>
                                <th>{{ __('Id') }}</th>
                                <th>{{ __('Title of the chapter') }}</th>
                                <th>{{ __('Author') }}</th>
                                <th>{{ __('Published On') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($chapters as $chapter)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $chapter->chapter_title }}</td>
                                <td>{{ $chapter->all_authors }}</td>
                                <td>{{ $chapter->chapter_publication_date }}</td>
                                <td>
                                    <a href="{{ route('admin.chapter.view', $chapter->id) }}" class="btn btn-warning btn-sm mb-2">{{ __('View chapter') }}</a>
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