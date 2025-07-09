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
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
    <h2>{{ $chapter->chapter_title }}</h2>
    <p><strong>{{ __('Keywords')}}:</strong> {{ $chapter->chapter_keywords }}</p>
    <p><strong>{{ __('Submitted by')}}:</strong> {{ $chapter->chapter_user->name ?? 'Unknown' }}</p>
    <iframe src="{{ asset('storage/' . $chapter->chapter_file_path) }}" width="100%" height="600px"></iframe>
    @if($chapter->chapter_status === 'published')
        <a href="{{ route('submission.download', ['type' => 'chapters', 'id' => $chapter->chapter_id]) }}" class="btn btn-primary btn-sm mt-3" id="downloadBtn">
        {{ __('Download PDF') }}
        </a>
        <form id="downloadForm" action="{{ route('submission.download', ['type' => 'chapters', 'id' => $chapter->chapter_id]) }}" method="GET" style="display: none;"></form>
    @else
    <p class="text-muted"><em>{{ __('Download available after publication.') }}</em></p>
    @endif
    
</div>
@endsection

@push('scripts')
    <script>
    document.getElementById('downloadBtn')?.addEventListener('click', function (e) {
        e.preventDefault();

        let alreadyDownloaded = @json($alreadyDownloaded);

        if (alreadyDownloaded) {
            Swal.fire({
                title: 'Already Downloaded',
                text: 'You have already downloaded this chapter. Do you wish to download again?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, download again',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('downloadForm').submit();
                }
            });
        } else {
            document.getElementById('downloadForm').submit();
        }
    });
</script>
@endpush
