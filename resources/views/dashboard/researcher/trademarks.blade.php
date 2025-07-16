@extends('layouts.researcher')

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-md-10">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card px-5">
                <h5 class="card-title mb-3 fw-bold">Trademark Filed</h5>

                @if(session('success'))  
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div> 
                @endif

                <form id="form" method="POST" action="{{ route('trademarks.store') }}" enctype="multipart/form-data" class="mb-4">
                    @csrf
                    <div class="col-12 g-2 mb-3">
                        <label for="title">{{ __('Trademark Title')}}:</label>
                         <input type="text" name="title" id="title" class="form-control">
                        <div id="error-title" class="text-danger"></div>
                    </div>
                    <div class="col-12 g-2 mb-3">
                        <label for="application_number">{{ __('Trademark Application Number')}}:</label>
                         <input type="text" name="application_number" id="application_number" class="form-control">
                        <div id="error-application_number" class="text-danger"></div>
                    </div>
                    <div class="col-12 g-2 mb-3">
                        <label for="application_date">{{ __('Trademark Application Date')}}:</label>
                        <input type="date" name="application_date" id="application_date" class="form-control">
                        <div id="error-application_date" class="text-danger"></div>
                    </div>                 
                    <div class="col-12 g-2 mb-3">
                        <label for="description">{{ __('Trademark Description')}}:</label>
                        <textarea name="description" id="description" class="form-control"></textarea>
                        <div id="error-description" class="text-danger"></div>
                    </div> 
                    <div class="col-12 g-2 mb-3">
                        <label for="certificate">{{ __('Upload the Trademark Registered Certificate')}}:</label>
                       <input type="file" name="certificate" id="certificate" class="form-control">
                        <div id="error-certificate" class="text-danger"></div>
                    </div> 
                    <button type="submit" class="btn btn-primary">Submit Trademark</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-10">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card">
                <h5 class="card-title mb-3">All trademarks</h5>
                @if ($trademarks->isEmpty()) 
                        {{ __('No trademarks filed') }}
                    @else
                    <div class="table-responsive">
                        <table class="table table-bordered border-dark-subtle table-hover">
                            <thead class="custom-header">
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Certificate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($trademarks as $trademark)
                                <tr>
                                    <td>{{ $trademark->title }}</td>
                                    <td>{{ ucfirst($trademark->status) }}</td>
                                    <td>
                                        @if($trademark->certificate_path)
                                            <a href="{{ asset('storage/' . $trademark->certificate_path) }}" target="_blank" class="btn btn-primary btn-sm">View</a>
                                        @else
                                            N/A
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
        title: @json(__('Trademark title')),
        description: @json(__('Trademark Description')),
        application_number: @json(__('Trademark Application Number')),
        application_date: @json(__('Trademark Application Date')),
        certificate: @json(__('Upload the Trademark Registered Certificate')),
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
        Swal.fire(@json(__('Success!')), @json(__('Trademark filed successfully!')), "success");
    });
</script>
@endpush