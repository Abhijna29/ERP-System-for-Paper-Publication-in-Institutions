@extends('layouts.patents')

@section('name')
{{ __('Copyright Granted')}}
@endsection

@section('number')
<label for="grant_number">{{ __('Grant Copyright Number')}}:</label>
<input type="text" class="form-control w-100" name="grant_number" id="grant_number">
<div id="error-grant_number" class="text-danger"></div>

@endsection