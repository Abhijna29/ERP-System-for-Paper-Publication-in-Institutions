@extends('layouts.admin')

@section('content')
<div class="container">
   
    <h3>{{ $chapter->chapter_title }}</h3>

    @if($chapter->file_path)
        <iframe src="{{ asset('storage/' . $chapter->file_path) }}" width="100%" height="600px"></iframe>
    @else
        <p>{{ __('No file uploaded.')}}</p>
    @endif

    <a class="btn btn-secondary" href=" {{ route('admin.bookChapters.published')}}">{{ __('Back')}}</a>
</div>
@endsection
