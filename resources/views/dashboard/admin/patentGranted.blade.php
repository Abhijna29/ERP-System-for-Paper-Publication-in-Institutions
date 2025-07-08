    @extends('layouts.patents')

    @section('name')
    {{ __('Patent Granted')}}
    @endsection

    @section('number')
        <label for="grant">{{ __('Grant Patent Number:')}}</label>
        <input type="text" class="form-control w-100" name="grant" id="grant">
        <div id="error-grant" class="text-danger"></div>
    @endsection