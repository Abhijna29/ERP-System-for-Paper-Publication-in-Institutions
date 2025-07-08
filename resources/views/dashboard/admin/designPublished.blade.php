@extends('layouts.patents')

@section('name')
{{ __('Design Published')}}
@endsection

@section('number')
<label for="number">{{ __('Publication Number:')}}</label>
<input type="text" class="form-control w-100" name="number" id="number">
<div id="error-number" class="text-danger"></div>
@endsection