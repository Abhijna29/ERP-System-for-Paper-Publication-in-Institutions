@extends('layouts.patents')

@section('name')
{{ __('Design Granted')}}
@endsection

@section('number')
<label for="grant_design_number">{{ __('Grant Design Number:')}}</label>
<input type="text" class="form-control w-100" name="grant_design_number" id="grant_design_number">
<div id="error-grant_design_number" class="text-danger"></div>
@endsection