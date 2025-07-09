@php
    // Map roles to layouts (adjust paths as per your project)
    $layouts = [
        'admin' => 'layouts.admin',
        'researcher' => 'layouts.researcher',
        'reviewer' => 'layouts.reviewer',
        'institution' => 'layouts.institution',
        'department' => 'layouts.department',
    ];

    // Pick layout or default to researcher layout
    $layout = $layouts[$role];
@endphp

@extends($layout)

@section('content')
<div class="container py-4">
    <h4>{{ __('Search Results for:')}} "{{ $query }}"</h4>

    <h5 class="mt-4">📚 {{ __('Local Research Papers')}}</h5>
    @if($localPapers->isEmpty())
        <p>{{ __('No local papers found.')}}</p>
    @else
        <div class="list-group">
            @foreach($localPapers as $paper)
                <a href="{{ route('papers.show', $paper->id) }}" class="list-group-item list-group-item-action">
                    <h5>{{ $paper->title }}</h5>
                    <p>{{ Str::limit($paper->abstract, 150) }}</p>
                    <p><strong>{{ __('Authors:')}}</strong> {{ $paper->user->name ?? 'Unknown' }}</p>
                    <p><strong>{{ __('Year:') }}</strong> {{ \Carbon\Carbon::parse($paper->publication_date)->year }}</p>
                </a>
            @endforeach
        </div>
    @endif

    <h4>Book Chapters (Local)</h4>
@if ($localChapters->count())
    @foreach ($localChapters as $chapter)
        <div class="card mb-3">
            <div class="card-body">
                <h5>{{ $chapter->chapter_title }}</h5>
                <p>{{ $chapter->abstract }}</p>
                <a href="{{ route('search.chapter.show', $chapter->id) }}" class="btn btn-sm btn-primary">View</a>
            </div>
        </div>
    @endforeach
    {{ $localChapters->links() }}
@else
    <p>No local book chapters found.</p>
@endif

    <h5 class="mt-5">🌐 {{ __('CrossRef Papers')}}</h5>
    @if(empty($crossRefResults))
    <p>{{ __('No external papers found.')}}</p>
@else
    <div class="list-group">
        @foreach($crossRefResults as $paper)
            <a href="{{ $paper['url'] }}" target="_blank" class="list-group-item list-group-item-action">
                <h5>{{ $paper['title'] }}</h5>
                <p><strong>{{ __('Authors:')}}</strong> {{ $paper['authors'] }}</p>
                <p><strong>{{ __('Year:')}}</strong> {{ $paper['published'] }}</p>
                <small>{{ __('DOI:')}} {{ $paper['doi'] }}</small>
            </a>
        @endforeach
    </div>

    {{-- Manual pagination --}}
    @php
        $totalPages = ceil($crossRefTotalResults / $crossRefPerPage);
    @endphp

    @if($totalPages > 1)
    <nav class="mt-3">
        <ul class="pagination justify-content-center">
            {{-- Previous --}}
            <li class="page-item {{ $crossRefPage <= 1 ? 'disabled' : '' }}">
                <a class="page-link"
                   href="{{ request()->fullUrlWithQuery(['crossref_page' => max(1, $crossRefPage - 1)]) }}">
                    {{ __('Previous')}}
                </a>
            </li>

            {{-- Page Counter --}}
            <li class="page-item disabled">
                <span class="page-link bg-light text-dark border-0">
                    {{ $crossRefPage }} / {{ $totalPages }}
                </span>
            </li>

            {{-- Next --}}
            <li class="page-item {{ $crossRefPage >= $totalPages ? 'disabled' : '' }}">
                <a class="page-link"
                   href="{{ request()->fullUrlWithQuery(['crossref_page' => min($totalPages, $crossRefPage + 1)]) }}">
                    {{ __('Next')}}
                </a>
            </li>
        </ul>
    </nav>
@endif
@endif

</div>
@endsection
