@extends('layouts.journal')

@section('journal_name')
{{ __('Others')}}
@endsection

@section('hidden_fields')
    <input type="hidden" name="indexing_database" value="others">
@endsection

@section('others')
    <label for="db">{{ __('Name of the database:')}}</label>
    <input type="text" class="form-control w-100"  name="db" id="db">
    <div id="error-db" class="text-danger"></div>
@endsection
