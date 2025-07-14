@extends('layouts.researcher')

@section('content')
@if(session('success'))
    <div class="alert alert-success text-center">
        {{ session('success') }}
    </div>
@endif

<div class="row g-4 justify-content-center">
    <div class="col-md-10">
        @if($subscription && $subscription->papers_used >= $subscription->plan->paper_limit)
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
               {{ __(' You have reached your paper submission limit as per your subscription.')}}
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
                <h5 class="card-title mb-3 fw-bold">{{ __('Submit Your Book Chapter')}}</h5>
                <form action="{{ route('researcher.book-chapters.store')}}" method="POST" enctype="multipart/form-data" id="form">
                    @csrf
                    <div class="row g-2 mb-3">
                        <label for="chapter_title">{{ __('Chapter Title')}}:</label>
                        <input type="text" class="form-control w-100 @error('chapter_title') is-invalid @enderror" name="chapter_title" id="chapter_title" value="{{ old('chapter_title') }}">
                        <div id="error-chapter_title" class="text-danger"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="keywords">{{ __('Keywords')}}:</label> <!-- Fixed for attribute -->
                        <input type="text" class="form-control w-100 @error('keywords') is-invalid @enderror" name="keywords" id="keywords" value="{{ old('keywords') }}">
                        <div id="error-keywords" class="text-danger"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="genre">{{ __('Select Genre') }}:</label>
                        <select class="form-control w-100" name="genre" id="genre">
                            <option value="">{{ __('Select Genre')}}</option>
                            @foreach($genres as $genre)
                                <option value="{{ $genre }}">{{ $genre }}</option>
                            @endforeach
                        </select>
                        <div id="error-genre" class="text-danger"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="book_id">{{ __('Select Book') }}:</label>
                        <select class="form-control w-100" name="book_id" id="book_id">
                            <option value="">{{ __('Select Book')}}</option>
                            {{-- Options will be loaded dynamically --}}
                        </select>
                        <div id="error-book_id" class="text-danger"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="chapter_file">{{ __('Upload Chapter (PDF)')}}:</label>
                        <input type="file" class="form-control w-100 @error('chapter_file') is-invalid @enderror" name="chapter_file" id="chapter_file" value="{{ old('chapter_file') }}" accept="application/pdf">
                        <div id="error-chapter_file" class="text-danger">
                            @error('chapter_file')
                                {{ $message }}
                            @enderror
                        </div>
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
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">{{ __('Submit Chapter')}}</button>
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

    const form = document.getElementById("form");

    const fieldNames = {
        chapter_title: "{{ __('Chapter Title') }}",
        genre: "{{ __('Genre') }}",
        book_id: "{{ __('Book') }}",
        keywords: "{{ __('Keywords') }}",
        chapter_file: "{{ __('Chapter File') }}"
    };

    const errorMessages = {
        required: "{{ __('Please Enter The') }} ",
        select: "{{ __('Please select a') }} ",
        upload: "{{ __('Please upload your Chapter.') }}"
    };

    function clearErrorMessages(input, errorElement) {
        input.addEventListener("input", () => {
            if (input.type === "file" && input.files.length > 0) {
                errorElement.textContent = "";
            } else if (input.type !== "file" && input.value.trim()) {
                errorElement.textContent = "";
            }
        });
    }

    form.addEventListener("submit", function (e) {
        e.preventDefault();
        let hasError = false;

        Object.keys(fieldNames).forEach((id) => {
            const input = document.getElementById(id);
            const error = document.getElementById(`error-${id}`);
            clearErrorMessages(input, error);

            if (id === "chapter_file") {
                if (input.files.length === 0) {
                    error.textContent = errorMessages.upload;
                    hasError = true;
                }
            } else if (id === "genre" || id === "book_id") {
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

        if (!hasError) form.submit();
    });

    document.getElementById('genre').addEventListener('change', function() {
        const genre = this.value;
        const bookSelect = document.getElementById('book_id');
        bookSelect.innerHTML = '<option value="">{{ __('Select Book') }}</option>';
        if (!genre) return;

        fetch("{{ route('books.by-genre', ['genre' => 'GENRE_PLACEHOLDER']) }}".replace('GENRE_PLACEHOLDER', encodeURIComponent(genre)))
            .then(res => res.json())
            .then(books => {
                books.forEach(book => {
                    const option = document.createElement('option');
                    option.value = book.id;
                    option.textContent = book.title;
                    bookSelect.appendChild(option);
                });
            })
            .catch(() => {
                bookSelect.innerHTML = `<option value="">{{ __("No books found") }}</option>`;
            });
    });
</script>
@endpush