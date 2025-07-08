@extends('layouts.department')

@section('content')
<div class="container">
   
    <h3>{{ $submission->title }}</h3>
    <p><strong>{{ __('Abstract:')}}</strong> {{ $submission->abstract }}</p>

    @if($submission->file_path)
        <iframe src="{{ asset('storage/' . $submission->file_path) }}" width="100%" height="600px"></iframe>
    @else
        <p>{{ __('No file uploaded.')}}</p>
    @endif

    <a href="{{route('department.submissions.index',['type'=>'paper'])}}" class="btn btn-secondary">{{ __('Back')}}</a>
</div>
@endsection
