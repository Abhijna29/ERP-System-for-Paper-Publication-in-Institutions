{{-- filepath: d:\xampp\htdocs\publication_project\resources\views\dashboard\admin\subCategory.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card">
                <h5 class="card-title mb-3 fw-bold">
                    {{ isset($editSubCategory) ? __('Edit Sub Category') : __('Create Sub Category') }}
                </h5>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ isset($editSubCategory) ? route('admin.subCategory.update', $editSubCategory->id) : route('admin.subCategory.store') }}" method="POST">
                    @csrf
                    @if(isset($editSubCategory))
                        @method('PUT')
                    @endif

                    <div class="col-12 mb-3">
                        <label for="name">{{ __('Sub Category Name') }}</label>
                        <input type="text" class="form-control w-100" name="name" id="name"
                               value="{{ old('name', $editSubCategory->name ?? '') }}">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label for="category_id">{{ __('Choose Category') }}</label>
                        <select class="form-select w-100" name="category_id" id="category_id">
                            <option value="">{{ __('Select a category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (isset($editSubCategory) && $editSubCategory->category_id == $category->id) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">
                            {{ isset($editSubCategory) ? __('Update') : __('Submit') }}
                        </button>
                        @if(isset($editSubCategory))
                            <a href="{{ route('admin.subCategory.index') }}" class="btn btn-secondary ms-2">{{ __('Cancel') }}</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card">
                <h5 class="card-title mb-3 fw-bold">{{ __('View Sub Categories') }}</h5>
                <div class="table-responsive">
                    <table class="table table-bordered border-dark-subtle table-hover fs-6" id="table">
                        <thead class="custom-header">
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Sub Category') }}</th>
                                <th>{{ __('Category') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subCategories as $subCategory)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $subCategory->name }}</td>
                                    <td>{{ $subCategory->category->name }}</td>
                                    <td>
                                        <a href="{{ route('admin.subCategory.edit', $subCategory->id) }}" class="btn btn-sm btn-success">{{ __('Edit') }}</a>

                                        <form action="{{ route('admin.subCategory.destroy', $subCategory->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger ">{{ __('Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">{{ __('No subcategories found.') }}</td>
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
