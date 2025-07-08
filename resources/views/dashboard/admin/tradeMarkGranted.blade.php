@extends('layouts.patents')

@section('name')
{{ __('Trade Mark Granted')}}
@endsection

@section('number')
<label for="grant_trade_number">{{ __('Grant Trade Mark Number:')}}</label>
<input type="text" class="form-control w-100" name="grant_trade_number" id="grant_trade_number">
<div id="error-grant_trade_number" class="text-danger"></div>
@endsection