@extends('layouts.researcher')

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-md-10">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card px-5">
                <h5 class="card-title mb-3 fw-bold">{{ __('Patent Filed')}}</h5>
                @if(session('success')) 
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form id="form" action="{{ route('researcher.patents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                    <div class="row g-2 mb-3">
                        <label for="investors_name">{{ __('Investors Name')}}:</label>
                        <input type="text" name="investors_name" id="investors_name" class="form-control w-100">
                        <div id="error-investors_name" class="text-danger"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="work_title">{{ __('Work Title')}}</label>
                        <input class="form-control w-100" name="work_title" id="work_title">
                        <div id="error-work_title" class="text-danger"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="work_description">{{ __('Work Description')}}:</label>
                        <textarea type="text" class="form-control w-100" name="work_description" id="work_description"></textarea>                    
                        <div id="error-work_description" class="text-danger"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="name">{{ __('Year')}}:</label>
                        <input type="text" class="form-control w-100" name="year" id="year">                    
                        <div id="error-year" class="text-danger"></div>
                    </div>

                    @if($papers->count())
                        <label>Link to Research Paper (optional)</label>
                        <select name="research_paper_id" class="form-select mb-2">
                            <option value="">-- None --</option>
                            @foreach($papers as $paper)
                                <option value="{{ $paper->id }}">{{ $paper->title }}</option>
                            @endforeach
                        </select>
                    @endif
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">{{ __('Submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-10">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card">
                <h5 class="card-title mb-3">My Patented Works</h5>
                @if ($patents->isEmpty()) 
                        {{ __('No patents filed') }}
                    @else
                    <div class="table-responsive">
                        <table class="table table-bordered border-dark-subtle table-hover">
                            <thead class="custom-header">
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($patents as $patent)
                                    <tr>
                                        <td>{{ $patent->work_title }}</td>
                                        <td><span class="badge bg-info">{{ ucfirst($patent->type) }}</span></td>
                                        <td>
                                            @if($patent->type == 'filed')
                                            <form action="{{ route('researcher.patents.markPublished', $patent->id) }}" method="POST">
                                                @csrf
                                                <input type="text" name="publication_number" class="form-control mb-2" placeholder="Enter Publication Number" required>
                                                <button class="btn btn-sm btn-success">Mark as Published</button>
                                            </form>

                                        @elseif($patent->certificate_path)
                                            <a href="{{ asset('storage/' . $patent->certificate_path) }}" target="_blank">View Certificate</a>
                                        @else
                                            <form action="{{ route('researcher.patents.uploadCertificate', $patent->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="file" name="certificate" class="form-control mb-1" required accept="application/pdf">
                                                <button type="submit" class="btn btn-sm btn-primary">Upload Certificate</button>
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
        investors_name: @json(__('Investors Name')),
        work_title: @json(__('Work Title')),
        work_description: @json(__('Work Description')),
        year: @json(__('Year')),
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
        Swal.fire(@json(__('Success!')), @json(__('Patent filed successfully!')), "success").then(() => {
                window.location.href = "{{ route('researcher.patents.index') }}";
            });;
    });
</script>
@endpush

