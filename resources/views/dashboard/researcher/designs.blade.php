@extends('layouts.researcher')

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-md-10">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card px-5">
                <h5 class="card-title mb-3 fw-bold">{{ __('File Design Rights')}}</h5>
                @if(session('success'))  
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> 
                    </div>
                @endif

                <form id="form" method="POST" action="{{ route('designs.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="col-12 g-2 mb-3">
                        <label for="title">{{ __('Design Title')}}:</label>
                         <input type="text" name="title" id="title" class="form-control">
                        <div id="error-title" class="text-danger"></div>
                    </div>
                    <div class="col-12 g-2 mb-3">
                        <label for="description">{{ __('Design Description')}}:</label>
                         <textarea name="description" id="description" class="form-control"></textarea>
                        <div id="error-description" class="text-danger"></div>
                    </div>
                    <div class="col-12 g-2 mb-3">
                        <label for="design_class">{{ __('Design Class')}}:</label>
                         <input type="text" name="design_class" id="design_class" class="form-control">
                        <div id="error-design_class" class="text-danger"></div>
                    </div>
                    <div class="col-12 g-2 mb-3">
                        <label for="registration_date">{{ __('Registration date')}}:</label>
                         <input type="date" name="registration_date" id="registration_date" class="form-control">
                        <div id="error-registration_date" class="text-danger"></div>
                    </div>
                    <div class="col-12 g-2 mb-3">
                        <label for="design_file">{{ __('Upload the design file')}}:</label>
                         <input type="file" name="design_file" id="design_file" class="form-control">
                        <div id="error-design_file" class="text-danger"></div>
                    </div>
                    <button class="btn btn-primary">{{ __('Submit')}}</button>
                </form>
            </div>
        </div>
    </div>


    <div class="col-md-10">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card">
                <h5 class="card-title mb-3">{{ __('Your Design Submissions')}}</h5>
                @if ($designs->isEmpty()) 
                        {{ __('No designs filed') }}
                    @else
                    <div class="table-responsive">
                        <table class="table table-bordered border-dark-subtle table-hover">
                            <thead class="custom-header">
                                <tr>
                                    <th>{{ __('Title')}}</th>
                                    <th>{{ __('Status')}}</th>
                                    <th>{{ __('Actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($designs as $design)
                                <tr>
                                    <td>{{ $design->title }}</td>
                                    <td>{{ ucfirst($design->status) }}</td>
                                    <td>
                                        @if($design->certificate_path)
                                            <a href="{{ asset('storage/' . $design->certificate_path) }}" target="_blank" class="btn btn-primary btn-sm">{{ __('View Certificate')}}</a>
                                            @else
                                            <form method="POST" action="{{ route('designs.uploadCertificate', $design->id) }}" enctype="multipart/form-data">
                                                @csrf
                                                <input type="file" name="certificate" class="form-control" required>
                                                <button class="btn btn-sm btn-success mt-2">{{ __('Upload Certificate')}}</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const form = document.getElementById("form");
    form.addEventListener("submit", function (e) {
    const fieldNames = {
        title: @json(__('Design Title')),
        description: @json(__('Design Description')),
        design_class: @json(__('Design Class')),
        registration_date: @json(__('Registration Date')),
        design_file: @json(__('Design File')),
    };

    let hasError = false;

    Object.keys(fieldNames).forEach((id) => {
        const input = document.getElementById(id);
        const error = document.getElementById(`error-${id}`);

        if (!input || !error) return;

        if (!input.value.trim()) {
            error.textContent = `{{ __('Please Enter The') }} ${fieldNames[id]}`;
            hasError = true;
        } else {
            error.textContent = "";
        }

        input.addEventListener("input", () => {
            if (input.value.trim()) {
                error.textContent = "";
            }
        });
    });

    if (hasError) {
        e.preventDefault(); // Block form submission
        return;
    }
        Swal.fire(@json(__('Success!')), @json(__('Design filed successfully!')), "success");
    });
</script>
@endpush