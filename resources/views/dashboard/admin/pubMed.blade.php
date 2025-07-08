@extends('layouts.journal')

@section('journal_name')
{{ __('Pub Med')}}
@endsection

@section('hidden_fields')
    <input type="hidden" name="indexing_database" value="pub-med">
@endsection
