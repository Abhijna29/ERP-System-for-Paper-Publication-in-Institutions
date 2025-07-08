@extends('layouts.patents')

@section('name')
{{ __('Trade Mark Published')}}
@endsection

@section('number')
<label for="trade_number">{{ __('Trade Mark Number:')}}</label>
<input type="text" class="form-control w-100" name="trade_number" id="trade_number">
<div id="error-trade_number" class="text-danger"></div>

@endsection