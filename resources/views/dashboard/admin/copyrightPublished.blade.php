@extends('layouts.patents')

@section('name')
{{ __('Copyright Published')}}
@endsection

@section('number')
<label for="copy_number">{{ __('Copyright Number:')}}</label>
<input type="text" class="form-control w-100" name="copy_number" id="copy_number">
<div id="error-copy_number" class="text-danger"></div>
@endsection