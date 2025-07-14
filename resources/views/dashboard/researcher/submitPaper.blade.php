@extends('layouts.researcher')

@section('content')
{{-- @if(session('error'))
    <div class="alert alert-danger text-center">
        {{ session('error') }}
    </div>
@endif --}}

@if(session('success'))
    <div class="alert alert-success text-center">
        {{ session('success') }}
    </div>
@endif

<div class="row g-4 justify-content-center">
    <div class="col-md-10">
        
        @if($subscription && $subscription->papers_used >= $subscription->plan->paper_limit)
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                You have reached your paper submission limit as per your subscription.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    // Disable all form fields and the submit button
                    document.querySelectorAll('form input, form textarea, form select, form button').forEach(function(el) {
                        el.disabled = true;
                    });
                });
            </script>
        @endif
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card px-5">
                <h5 class="card-title mb-3 fw-bold">{{ __('Submit Your Paper')}}</h5>
                <form action="{{ route('papers.store')}}" method="POST" enctype="multipart/form-data" id="form">
                    @csrf
                    <div class="row g-2 mb-3">
                        <label for="title">{{ __('Title of your paper:')}}</label>
                        <input type="text" class="form-control w-100 @error('title') is-invalid @enderror" name="title" id="title" value="{{ old('title') }}">
                        <div id="error-title" class="text-danger"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="abstract">{{ __('Abstract')}}</label>
                        <textarea class="form-control w-100 @error('abstract') is-invalid @enderror" id="abstract" name="abstract" rows="3">{{ old('abstract') }}</textarea>
                        <div id="error-abstract" class="text-danger"></div>
                        <p class="m-0"><span id="wordCount">0 /250 {{ __('words')}} </span></p>
                        <div id="abstractError" class="text-danger d-none"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="keywords">{{ __('Keywords:')}}</label> 
                        <input type="text" class="form-control w-100 @error('keywords') is-invalid @enderror" name="keywords" id="keywords" value="{{ old('keywords') }}">
                        <div id="error-keywords" class="text-danger"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="category">{{ __('Choose a Category:')}}</label>
                        <select class="form-control w-100 @error('category') is-invalid @enderror" name="category" id="category" value="{{ old('category') }}">
                            <option value="">{{ __('Select Category')}}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <div id="error-category" class="text-danger">{{ $errors->first('category') }}</div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="subCategory">{{ __('Choose a Sub category:')}}</label>
                        <select class="form-control w-100 @error('subCategory') is-invalid @enderror" name="subCategory" id="subCategory"  value="{{ old('subCategory') }}" disabled>
                            <option value="">{{ __('Select Sub Category')}}</option>
                        </select>
                        <div id="error-subCategory" class="text-danger">{{ $errors->first('subCategory') }}</div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="childCategory">{{ __('Choose a Child category:')}}</label>
                        <select class="form-control w-100 @error('childCategory') is-invalid @enderror" name="childCategory" id="childCategory"  value="{{ old('childCategory') }}" disabled>
                            <option value="">{{ __('Select Child Category')}}</option>
                        </select>
                        <div id="error-childCategory" class="text-danger">{{ $errors->first('childCategory') }}</div>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="collaborationCheckbox">
                        <label class="form-check-label" for="collaborationCheckbox">
                            {{ __('This publication has collaborations')}}
                        </label>
                    </div>
                    <div id="collaborationFields" style="display: none;">
                        <h5 class="my-3">{{ __('This publication is in collaboration with')}}:</h5>
                        <div class="row">
                            {{-- Foreign Authors --}}
                            <div class="col-lg-12 my-2">
                                <label class="mb-3">a. {{ __('Foreign University/Institution') }}</label>
                                <div id="foreignAuthorsContainer">
                                    <div class="foreign-author-row row mb-3">
                                        <div class="col-lg-5">
                                            <input type="text" name="author_foreign[]" class="form-control mb-2" placeholder="Author Name">
                                        </div>
                                        <div class="col-lg-5">
                                            <input type="text" name="affiliation_foreign[]" class="form-control mb-2" placeholder="Affiliation">
                                        </div>
                                        <div class="col-2">
                                            <button type="button" class="btn btn-sm btn-success btn-add-foreign-author"><i class="fa-solid fa-plus"></i></button>
                                            <button type="button" class="btn btn-sm btn-danger btn-remove-foreign-author" style="display: none;"><i class="fa-solid fa-minus"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Indian Authors --}}
                            <div class="col-lg-12 my-2">
                                <label class="mb-3">b. {{ __('Other Indian University/Institution') }}</label>
                                <div id="indianAuthorsContainer">
                                    <div class="indian-author-row row mb-3">
                                        <div class="col-lg-5">
                                            <input type="text" name="author_indian[]" class="form-control mb-2" placeholder="Author Name">
                                        </div>
                                        <div class="col-lg-5">
                                            <input type="text" name="affiliation_indian[]" class="form-control mb-2" placeholder="Affiliation">
                                        </div>
                                        <div class="col-2">
                                            <button type="button" class="btn btn-sm btn-success btn-add-indian-author"><i class="fa-solid fa-plus"></i></button>
                                            <button type="button" class="btn btn-sm btn-danger btn-remove-indian-author" style="display: none;"><i class="fa-solid fa-minus"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="paper_file">{{ __('Upload Paper (PDF):')}}</label>
                        <input type="file" class="form-control w-100 @error('paper_file') is-invalid @enderror" name="paper_file" id="paper_file" value="{{ old('paper_file') }}" accept="application/pdf">
                        <div id="error-paper_file" class="text-danger">
                            @error('paper_file')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">{{ __('Submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const hasSubscription = {{ $hasSubscription ? 'true' : 'false' }};

    if (!hasSubscription) {
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                icon: 'error',
                title: 'Subscription Required',
                text: 'Your institution does not have an active subscription. Please contact your institution administrator.',
                confirmButtonText: 'Go Back'
                }).then(() => {
                    window.location.href = "{{ route('researcher.dashboard') }}";
                });

                // Disable the form completely
                document.querySelectorAll('form input, form textarea, form select, form button').forEach(el => el.disabled = true);
            });
        }

    const checkbox = document.getElementById('collaborationCheckbox');
    const collaborationFields = document.getElementById('collaborationFields');

    // Initially hide or show based on current state
    collaborationFields.style.display = checkbox.checked ? 'block' : 'none';

    checkbox.addEventListener('change', function () {
        collaborationFields.style.display = this.checked ? 'block' : 'none';
    });

    const sections = ['foreign', 'indian'];

    sections.forEach(function(type) {
        const containerId = `${type}AuthorsContainer`;
        const authorsContainer = document.getElementById(containerId);

        authorsContainer.addEventListener('click', function (e) {
            const target = e.target.closest('button');

            if (!target) return;

            if (target.classList.contains(`btn-add-${type}-author`)) {
                const firstRow = authorsContainer.querySelector(`.${type}-author-row`);
                const newRow = firstRow.cloneNode(true);

                // Clear input fields
                newRow.querySelectorAll('input').forEach(input => input.value = '');

                authorsContainer.appendChild(newRow);
                updateButtonsVisibility(type);
            }

            if (target.classList.contains(`btn-remove-${type}-author`)) {
                const rows = authorsContainer.querySelectorAll(`.${type}-author-row`);
                if (rows.length > 1) {
                    const rowToRemove = target.closest(`.${type}-author-row`);
                    rowToRemove.remove();
                    updateButtonsVisibility(type);
                }
            }
        });

        // Initial button visibility
        updateButtonsVisibility(type);
    });

    function updateButtonsVisibility(type) {
        const container = document.getElementById(`${type}AuthorsContainer`);
        const rows = container.querySelectorAll(`.${type}-author-row`);

        rows.forEach((row, index) => {
            const addBtn = row.querySelector(`.btn-add-${type}-author`);
            const removeBtn = row.querySelector(`.btn-remove-${type}-author`);

            if (addBtn) addBtn.style.display = index === rows.length - 1 ? 'inline-block' : 'none';
            if (removeBtn) removeBtn.style.display = rows.length > 1 ? 'inline-block' : 'none';
        });
    }


    // const hasSubscription = {{ $subscription ? 'true' : 'false' }};
    const form = document.getElementById("form");
    const title = document.getElementById("title");
    const abstract = document.getElementById("abstract");
    const keywords = document.getElementById("keywords");
    const paper_file = document.getElementById("paper_file");

    const categorySelect = document.getElementById("category");
    const subCategorySelect = document.getElementById("subCategory");
    const childCategorySelect = document.getElementById("childCategory");

    // Category -> Subcategory dropdown
    categorySelect.addEventListener("change", function () {
        const categoryId = this.value;
        subCategorySelect.disabled = true;
        childCategorySelect.disabled = true;
        subCategorySelect.innerHTML = '<option value="">Select Sub Category</option>';
        childCategorySelect.innerHTML = '<option value="">Select Child Category</option>';

        if (categoryId) {
            fetch(`{{ url('/researcher/subcategories') }}/${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(sub => {
                        const option = document.createElement("option");
                        option.value = sub.id;
                        option.textContent = sub.name;
                        subCategorySelect.appendChild(option);
                    });
                    subCategorySelect.disabled = false;
                })
                .catch(error => console.error("Error fetching subcategories:", error));
        }
    });

    // Subcategory -> Child category dropdown
    subCategorySelect.addEventListener("change", function () {
        const subCategoryId = this.value;
        childCategorySelect.disabled = true;
        childCategorySelect.innerHTML = '<option value="">Select Child Category</option>';

        if (subCategoryId) {
            fetch(`{{ url('/researcher/childcategories') }}/${subCategoryId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(child => {
                        const option = document.createElement("option");
                        option.value = child.id;
                        option.textContent = child.name;
                        childCategorySelect.appendChild(option);
                    });
                    childCategorySelect.disabled = false;
                })
                .catch(error => console.error("Error fetching child categories:", error));
        }
    });

    // Real-time error clearing
    const clearErrorMessages = (input, errorElement) => {
        input.addEventListener("input", () => {
            if (input.type === "file" && input.files.length > 0) {
                errorElement.textContent = "";
            } else if (input.type !== "file" && input.value.trim()) {
                errorElement.textContent = "";
            }
        });
    };

    // Form validation and submission
    form.addEventListener("submit", function (e) {
        e.preventDefault();
        let hasError = false;

        const fieldNames = {
            title: "{{ __('Title of your paper') }}",
            abstract: "{{ __('Abstract') }}",
            keywords: "{{ __('Keywords') }}",
            category: "{{ __('Category') }}",
            subCategory: "{{ __('Sub Category') }}",
            childCategory: "{{ __('Child Category') }}",
            paper_file: "{{ __('Paper File') }}"
        };

        const errorMessages = {
            required: "{{ __('Please Enter The') }} ",
            select: "{{ __('Please select a') }} ",
            upload: "{{ __('Please upload your paper.') }}"
        };

        ["title", "abstract", "keywords", "category", "subCategory", "childCategory", "paper_file"].forEach((id) => {
            const input = document.getElementById(id);
            const error = document.getElementById(`error-${id}`);

            // Clear error on input change
            clearErrorMessages(input, error);

            if (id === "paper_file") {
                if (input.files.length === 0) {
                    error.textContent = errorMessages.upload;
                    hasError = true;
                }
            } else if (["category", "subCategory", "childCategory"].includes(id)) {
                if (!input.value) {
                    error.textContent = errorMessages.select + fieldNames[id];
                    hasError = true;
                }
            } else {
                if (!input.value.trim()) {
                    error.textContent = errorMessages.required + fieldNames[id];
                    hasError = true;
                }
            }
        });

        if (hasError) return;
        form.submit(); // Only submit if no errors
    });
</script>
@endpush