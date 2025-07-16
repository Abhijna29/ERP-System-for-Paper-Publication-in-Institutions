@extends('layouts.researcher')

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-md-10">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card px-5">
                <h5 class="card-title mb-3 fw-bold">{{ __('Submit Work for Copyright Tracking')}}</h5>
                @if(session('success'))  
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div> 
                @endif
                <form id="form" action="{{ route('researcher.copyrights.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="col-12 g-2 mb-3">
                        <label for="title">{{ __('Copyright Title')}}:</label>
                         <input type="text" name="title" id="title" class="form-control">
                        <div id="error-title" class="text-danger"></div>
                    </div>
                     <div class="col-12 g-2 mb-3">
                        <label for="type_of_work">{{ __('Type of Work')}}</label>
                        <select name="type_of_work" id="type_of_work" class="form-select">
                            <option value=""></option>
                            <option value="literary">{{ __('Literary')}}</option>
                            <option value="artistic">{{ __('Artistic')}}</option>
                            <option value="software">{{ __('Software')}}</option>
                            <option value="musical">{{ __('Musical')}}</option>
                            <option value="dramatic">{{ __('Dramatic')}}</option>
                        </select>
                         <div id="error-type_of_work" class="text-danger"></div>
                    </div>
                    <div class="col-12 g-2 mb-3">
                        <label for="registration_number">{{ __('Registration number')}}</label>
                        <input type='text' name="registration_number" id="registration_number" class="form-control" rows="3"></input>
                        <div id="error-registration_number" class="text-danger"></div>
                    </div>
                    <div class="col-12 g-2 mb-3">
                        <label for="registration_date">{{ __('Registration date')}}</label>
                        <input type='date' name="registration_date" id="registration_date" class="form-control"></input>
                        <div id="error-registration_date" class="text-danger"></div>
                    </div>
                    <button class="btn btn-primary">{{ __('Submit')}}</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-10">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card">
                <h5 class="card-title mb-3">{{ __('My Copyrighted Works')}}</h5>
                @if ($copyrights->isEmpty()) 
                        {{ __('No copyrights filed') }}
                    @else
                    <div class="table-responsive">
                        <table class="table table-bordered border-dark-subtle table-hover">
                            <thead class="custom-header">
                                <tr>
                                <th>{{ __('Title')}}</th>
                                <th>{{ __('Type')}}</th>
                                <th>{{ __('Status')}}</th>
                                <th>{{ __('Actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($copyrights as $c)
                                <tr>
                                    <td>{{ $c->title }}</td>
                                    <td>{{ ucfirst($c->type_of_work) }}</td>
                                    <td>{{ ucfirst($c->status) }}</td>
                                    <td>
                                        @if($c->certificate_path)
                                            <a href="{{ asset('storage/' . $c->certificate_path) }}" target="_blank" class="btn btn-primary btn-sm">{{ __('View Certificate')}}</a>
                                        @else
                                            <form action="{{ route('researcher.copyrights.uploadCertificate', $c->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" name="certificate" accept="application/pdf" class="form-control mb-1" required>
                                            <button class="btn btn-sm btn-success">{{ __('Upload Certificate')}}</button>
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
        title: @json(__('Copyright title')),
        description: @json(__('Copyright Description')),
        registration_number: @json(__('Registration Number')),
        registration_date: @json(__('Registration Date')),
        type_of_work: @json(__('Type of Work')),
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
        Swal.fire(@json(__('Success!')), @json(__('Copyright filed successfully!')), "success");
    });
</script>
@endpush