@extends('layouts.admin')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card">
                <h5 class="card-title mb-3 fw-bold">
                    {{ isset($editCategory) ? __('Edit Category') : __('Create Category') }}
                </h5>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible">{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible">{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ isset($editCategory) ? route('admin.category.update', $editCategory->id) : route('admin.category.store') }}" method="POST">
                    @csrf
                    @if(isset($editCategory))
                        @method('PUT')
                    @endif

                    <div class="col-12 mb-3">
                        <label for="name">{{ __('Category Name') }}</label>
                        <input type="text" class="form-control w-100" name="name" id="name"
                               value="{{ old('name', $editCategory->name ?? '') }}">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">
                            {{ isset($editCategory) ? __('Update') : __('Submit') }}
                        </button>
                        @if(isset($editCategory))
                            <a href="{{ route('admin.category.index') }}" class="btn btn-secondary ms-2">{{ __('Cancel') }}</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card">
                <h5 class="card-title mb-3 fw-bold">{{ __('View Categories') }}</h5>
                <div class="table-responsive">
                    <table class="table table-bordered border-dark-subtle table-hover fs-6" id="table">
                        <thead class="custom-header">
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Category Name') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $category)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>
                                        <a href="{{ route('admin.category.edit', $category->id) }}" class="btn btn-sm btn-success">
                                            {{ __('Edit') }}
                                        </a>

                                        <form action="{{ route('admin.category.destroy', $category->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">{{ __('No categories found.') }}</td>
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
