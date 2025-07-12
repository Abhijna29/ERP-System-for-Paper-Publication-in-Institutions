@extends('layouts.journal')

@section('journal_name')
{{ __('Web of Science')}}
@endsection

@section('hidden_fields')
    <input type="hidden" name="indexing_database" value="web-of-Sci">
@endsection

@section('percentile')
<label for="percentile">{{ __('Percentile of the Journal')}}: </label>
<div class="col-lg-9">
    <input type="text" class="form-control w-100" name="percentile" id="percentile">
    <div id="error-percentile" class="text-danger"></div>
</div>
@endsection
