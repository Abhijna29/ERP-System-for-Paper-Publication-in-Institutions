@extends('layouts.admin')

@section('content')
<div class="container">
   
    <h3>{{ $paper->title }}</h3>
    <p><strong>{{ __('Abstract:')}}</strong> {{ $paper->abstract }}</p>

    @if($paper->file_path)
        <iframe src="{{ asset('storage/' . $paper->file_path) }}" width="100%" height="600px"></iframe>
    @else
        <p>{{ __('No file uploaded.')}}</p>
    @endif
<a class="btn btn-secondary" href=" {{ route('admin.papers')}}">{{ __('Back')}}</a>
</div>
@endsection
