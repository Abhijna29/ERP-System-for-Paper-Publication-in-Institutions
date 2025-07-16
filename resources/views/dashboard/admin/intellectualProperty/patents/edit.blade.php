@extends('layouts.admin')

@section('content')
<div class="card bg-white border-0 rounded-4 shadow">
    <div class="card-body user-card">
        <h5 class="card-title mb-3">{{ __('Edit Patent Status')}}</h5>

        @if($patent->certificate_path)
            <a href="{{ asset('storage/' . $patent->certificate_path) }}" target="_blank" class="btn btn-outline-secondary mb-2">
                📎 {{ __('View Uploaded Certificate (PDF)')}}
            </a>
        @endif

        <form action="{{ route('admin.patents.update', $patent->id) }}" method="POST">
            @csrf @method('PATCH')

            <select name="type" class="form-select mb-2" id="type-select">
                <option value="filed" {{ $patent->type == 'filed' ? 'selected' : '' }}>{{ __('Filed')}}</option>
                <option value="published" {{ $patent->type == 'published' ? 'selected' : '' }}>{{ __('Published')}}</option>
                <option value="granted" {{ $patent->type == 'granted' ? 'selected' : '' }}>{{ __('Granted')}}</option>
            </select>

            <div id="published-field" class="mb-2" style="display: none;">
                <input type="text" name="publication_number" class="form-control" placeholder="Publication Number"
                    value="{{ old('publication_number', $patent->publication_number) }}">
            </div>

            <div id="granted-field" class="mb-2" style="display: none;">
                <input type="text" name="grant_number" class="form-control" placeholder="Grant Number"
                    value="{{ old('grant_number', $patent->grant_number) }}">
            </div>

            <button type="submit" class="btn btn-success">{{ __('Update')}}</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeSelect = document.getElementById('type-select');
        const publishedField = document.getElementById('published-field');
        const grantedField = document.getElementById('granted-field');

        function toggleFields() {
            const selected = typeSelect.value;
            publishedField.style.display = selected === 'published' ? 'block' : 'none';
            grantedField.style.display = selected === 'granted' ? 'block' : 'none';
        }

        typeSelect.addEventListener('change', toggleFields);
        toggleFields(); // call on load to handle pre-selected value
    });
</script>
@endpush
