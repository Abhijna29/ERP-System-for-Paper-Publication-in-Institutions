@extends('layouts.institution')

@section('content')
<div class="container py-4">
    @foreach ($plans as $plan)
    <div class="card mb-3">
        <div class="card-body">
            <h5>{{ $plan->name }} - {{ $plan->duration }} - ₹{{ $plan->price }}</h5>
            <p>{{ __('Objective')}}: {{$plan->objective}}</p>
            <p>{{ __('Features')}}: {{$plan->summary}}</p>
            <form method="POST" action="{{ route('institution.subscribe') }}">
                @csrf
                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                <button class="btn btn-primary">{{ __('Subscribe')}}</button>
            </form>
        </div>
    </div>
    @endforeach
    
</div>
@endsection
