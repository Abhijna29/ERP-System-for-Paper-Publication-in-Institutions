@extends('layouts.admin')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card">
                <h5 class="card-title mb-3 fw-bold">
                    {{ isset($childCategory) ? __('Edit Child Category') : __('Create Child Category') }}
                </h5>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ isset($childCategory) ? route('admin.childCategory.update', $childCategory->id) : route('admin.childCategory.store') }}" method="POST" id="childCategoryForm">
                    @csrf
                    @if(isset($childCategory))
                        @method('PUT')
                    @endif

                    <div class="col-12 mb-3">
                        <label for="childCategory">{{ __('Child Category Name') }}</label>
                        <input type="text" class="form-control w-100" name="childCategory" id="childCategory" value="{{ old('childCategory', $childCategory->name ?? '') }}">
                        @error('childCategory')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label for="category_id">{{ __('Choose Category') }}</label>
                        <select class="form-select w-100" name="category_id" id="category_id">
                            <option value="">{{ __('Select a category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (isset($childCategory) && $childCategory->category_id == $category->id) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label for="sub_category_id">{{ __('Choose Sub Category') }}</label>
                        <select class="form-select w-100" name="sub_category_id" id="sub_category_id">
                            <option value="">{{ __('Select a sub category') }}</option>
                        </select>
                        @error('sub_category_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">{{ isset($childCategory) ? __('Update') : __('Submit') }}</button>
                        @if(isset($childCategory))
                            <a href="{{ route('admin.childCategory.index') }}" class="btn btn-secondary ms-2">{{ __('Cancel') }}</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card">
                <h5 class="card-title mb-3 fw-bold">{{ __('View Child Categories') }}</h5>
                <div class="table-responsive">
                    <table class="table table-bordered border-dark-subtle table-hover fs-6" id="table">
                        <thead class="custom-header">
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Child Category') }}</th>
                                <th>{{ __('Sub Category') }}</th>
                                <th>{{ __('Category') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($childCategories as $childCategory)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $childCategory->name }}</td>
                                    <td>{{ $childCategory->subCategory->name }}</td>
                                    <td>{{ $childCategory->category->name }}</td>
                                    <td>
                                        <a href="{{ route('admin.childCategory.edit', $childCategory->id) }}" class="btn btn-sm btn-success mb-1">{{ __('Edit') }}</a>
                                        <form action="{{ route('admin.childCategory.destroy', $childCategory->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger mb-1">{{ __('Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">{{ __('No child categories found.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const selectedSubCategory = "{{ old('sub_category_id', $childCategory->sub_category_id ?? '') }}";

    $(document).ready(function() {
        // $('#childCategoryForm').on('submit', function(e) {
        //     // e.preventDefault();
        //     let form = $(this);
        //     let url = form.attr('action');
        //     let method = form.find('input[name="_method"]').val() || 'POST';

        //     $.ajax({
        //         url: url,
        //         type: method,
        //         data: form.serialize(), 
        //     });
        // });

        $('#sub_category_id').prop('disabled', true);
        $('#category_id').on('change', function() {
            var categoryID = $(this).val();
            if(categoryID) {
                $.ajax({
                    url: "{{ url('/admin/get-subcategories') }}/" + categoryID,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('#sub_category_id').empty();
                        $('#sub_category_id').append('<option value="">' + @json(__('Select a sub category')) + '</option>');
                        $.each(data, function(key, value) {
                            $('#sub_category_id').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                        });
                        $('#sub_category_id').prop('disabled', false);
                    }
                });
            } else {
                $('#sub_category_id').empty();
                $('#sub_category_id').append('<option value="">' + @json(__('Select a sub category')) + '</option>');
                $('#sub_category_id').prop('disabled', false);
            }
        });
        if($('#category_id').val()) {
            var categoryID = $('#category_id').val();
            $.ajax({
                url: "{{ url('/admin/get-subcategories/') }}/" + categoryID,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#sub_category_id').empty();
                    $.each(data, function(key, value) {
                    $('#sub_category_id').append(
                        '<option value="'+ value.id +'" ' + 
                        (value.id == selectedSubCategory ? 'selected' : '') + 
                        '>' + value.name + '</option>'
                    );
                    });

                    $('#sub_category_id').prop('disabled', false); 
                }
            });
        }
    });
</script>
@endpush
