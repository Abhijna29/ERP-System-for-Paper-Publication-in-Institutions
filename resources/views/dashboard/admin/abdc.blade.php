@extends('layouts.journal')

@section('journal_name')
{{ __('ABDC')}}
@endsection

@section('hidden_fields')
    <input type="hidden" name="indexing_database" value="abdc">
@endsection
    