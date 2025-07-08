@extends('layouts.admin')

@section('content')
<div class="row g-4 d-flex justify-content-center">
    <div class="col-lg-10">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card px-5">
                <h5 class="card-title mb-3 fw-bold">{{ __('Book Chapter Metadata')}}</h5>

                <div class="container mt-4">
                    <p>{{ __('Select a chapter to Enter Metadata')}}</p>

                    @if($chapters->isEmpty())
                        <div class="alert alert-info">{{ __('No chapters are currently ready to publish.')}}</div>
                    @else
                        <select id="chapterSelect" class="form-select mb-3" required>
                            <option value="" disabled selected>{{ __('Select a chapter')}}</option>
                            @foreach($chapters as $chapter)
                                <option value="{{ $chapter->id }}" data-chapter_title="{{ $chapter->chapter_title }}" data-author="{{ $chapter->user->name }}" data-book_title="{{ $chapter->book->title ?? '' }}">
                                    {{ $chapter->chapter_title }} (By: {{ $chapter->user->name }})
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <form id="form" action="{{ route('bookChapter.submit') }}" method="POST" style="display: none;">
                    @csrf
                     @yield('hidden_fields')
                    <input type="hidden" name="book_chapter_id" id="chapter_id">
                    <div class="col-12 mb-3">
                        @yield('others')
                    </div>
                    <div class="row g-2">
                        <label for="author">{{ __('Author') }}</label>
                        <div id="authorsContainer">
                            <div class="row g-2 mb-3 author-row">
                                <div class="col-lg-3">
                                    <input type="text" name="author_first_names[]" class="form-control w-100" placeholder="First name" value="{{ old('author_first_names.0') }}">
                                </div>
                                <div class="col-lg-3">
                                    <input type="text" name="author_middle_names[]" class="form-control w-100" placeholder="Middle name" value="{{ old('author_middle_names.0') }}">
                                </div>
                                <div class="col-lg-3">
                                    <input type="text" name="author_last_names[]" class="form-control w-100" placeholder="Last name" value="{{ old('author_last_names.0') }}">
                                </div>
                                <div class="col-lg-3">
                                    <button type="button" class="btn btn-primary btn-add-author"><i class="fa-solid fa-plus"></i></button>
                                    <button type="button" class="btn btn-danger btn-remove-author" style="display: none;"><i class="fa-solid fa-minus"></i></button>
                                </div>
                            </div>
                        </div>
                        <div id="error-author" class="text-danger"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="publication_date">{{ __('Publication Year:') }}</label>
                        <input type="date" class="form-control w-100" name="publication_date" id="publication_date" placeholder="Enter year" value="{{ old('publication_date') }}">
                        <div id="error-publication_date" class="text-danger"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="chapter_title">{{ __('Title of the Study:') }}</label>
                        <input type="text" class="form-control w-100" name="chapter_title" id="chapter_title" value="{{ old('chapter_title') }}">
                        <div id="error-chapter_title" class="text-danger"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="page">{{ __('Page Range:') }}</label>
                        <input type="text" class="form-control w-100" name="page" id="page" value="{{ old('page') }}">
                        <div id="error-page" class="text-danger"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="doi">{{ __('DOI:') }}</label>
                        <input type="text" class="form-control w-100" name="doi" id="doi" value="{{ old('doi') }}">
                        <div id="error-doi" class="text-danger"></div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="collaborationCheckbox">
                        <label class="form-check-label" for="collaborationCheckbox">
                            {{ __('This publication has collaborations')}}
                        </label>
                    </div>
                    <div id="collaborationFields" style="display: none;">
                        <h5 class="my-4">{{ __('This publication is in collaboration with:')}}</h5>
                        <div class="row">
                            <div class="col-lg-6 my-2">
                                <label class="mb-3"> {{ __('a. Foreign University/Institution')}} </label>
                                <div class="row mb-3">
                                    <label for="author_foreign">{{ __('Name of the Author:')}} </label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control w-100" name="author_foreign" id="author_foreign" value="{{ old('author_foreign') }}">
                                        <div id="error-author_foreign" class="text-danger"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="affiliation_foreign">{{ __('Affiliation:')}} </label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control w-100" name="affiliation_foreign" id="affiliation_foreign" value="{{ old('affiliation_foreign') }}">
                                        <div id="error-affiliation_foreign" class="text-danger"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 my-2">
                                <label class="mb-3">{{ __('b. Other Indian University/institution')}} </label>
                                <div class="row mb-3">
                                    <label for="author_indian">{{ __('Name of the Author:')}} </label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control w-100" name="author_indian" id="author_indian" value="{{ old('author_indian') }}">
                                        <div id="error-author_indian" class="text-danger"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="affiliation_indian">{{ __('Affiliation:')}} </label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control w-100" name="affiliation_indian" id="affiliation_indian" value="{{ old('affiliation_indian') }}">
                                        <div id="error-affiliation_indian" class="text-danger"></div>
                                    </div>
                                </div>
                            </div>
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
    document.addEventListener('DOMContentLoaded', () => {
        const chapterSelect = document.getElementById('chapterSelect');
        const form = document.getElementById('form');
        const chapterIdInput = document.getElementById('chapter_id');
        const titleInput = document.getElementById('chapter_title');
        const authorsContainer = document.getElementById('authorsContainer');

        // Function to reset author rows to a single empty row
        function resetAuthorRows() {
            authorsContainer.innerHTML = `
                <div class="row g-2 mb-3 author-row">
                    <div class="col-lg-3">
                        <input type="text" name="author_first_names[]" class="form-control w-100" placeholder="First name">
                    </div>
                    <div class="col-lg-3">
                        <input type="text" name="author_middle_names[]" class="form-control w-100" placeholder="Middle name">
                    </div>
                    <div class="col-lg-3">
                        <input type="text" name="author_last_names[]" class="form-control w-100" placeholder="Last name">
                    </div>
                    <div class="col-lg-3">
                        <button type="button" class="btn btn-primary btn-add-author"><i class="fa-solid fa-plus"></i></button>
                        <button type="button" class="btn btn-danger btn-remove-author" style="display: none;"><i class="fa-solid fa-minus"></i></button>
                    </div>
                </div>
            `;
            updateButtonsVisibility();
        }

        // Function to parse author name
        function parseAuthorName(fullName) {
            const nameParts = fullName.trim().split(/\s+/);
            let firstName = '';
            let middleName = '';
            let lastName = '';

            if (nameParts.length === 1) {
                firstName = nameParts[0];
            } else if (nameParts.length === 2) {
                firstName = nameParts[0];
                lastName = nameParts[1];
            } else if (nameParts.length >= 3) {
                firstName = nameParts[0];
                lastName = nameParts[nameParts.length - 1];
                middleName = nameParts.slice(1, -1).join(' ');
            }

            return { firstName, middleName, lastName };
        }

        // Show/hide form and autofill fields based on chapter selection
        if (chapterSelect) {
            chapterSelect.addEventListener('change', () => {
                if (chapterSelect.value) {
                    const selectedOption = chapterSelect.options[chapterSelect.selectedIndex];
                    const chapter_title = selectedOption.getAttribute('data-chapter_title');
                    const authorName = selectedOption.getAttribute('data-author');

                    // Show form and update hidden input
                    form.style.display = 'block';
                    chapterIdInput.value = chapterSelect.value;

                    // Reset author rows
                    resetAuthorRows();

                    // Autofill title and author fields
                    titleInput.value = chapter_title || '';

                    const { firstName, middleName, lastName } = parseAuthorName(authorName);
                    const firstNameInput = authorsContainer.querySelector('input[name="author_first_names[]"]');
                    const middleNameInput = authorsContainer.querySelector('input[name="author_middle_names[]"]');
                    const lastNameInput = authorsContainer.querySelector('input[name="author_last_names[]"]');
                    firstNameInput.value = firstName || '';
                    middleNameInput.value = middleName || '';
                    lastNameInput.value = lastName || '';
                } else {
                    form.style.display = 'none';
                    chapterIdInput.value = '';
                    titleInput.value = '';
                    resetAuthorRows();
                }
            });
        }

        function updateButtonsVisibility() {
            const authorRows = authorsContainer.querySelectorAll('.author-row');
            authorRows.forEach((row, idx) => {
                const addBtn = row.querySelector('.btn-add-author');
                const removeBtn = row.querySelector('.btn-remove-author');

                // Only last row shows plus button
                addBtn.style.display = idx === authorRows.length - 1 ? 'inline-block' : 'none';

                // Show minus button only if there are 2 or more rows
                removeBtn.style.display = authorRows.length > 1 ? 'inline-block' : 'none';
            });
        }

        authorsContainer.addEventListener('click', (event) => {
            const target = event.target.closest('button');

            if (!target) return;

            if (target.classList.contains('btn-add-author')) {
                // Clone first author row
                const firstAuthorRow = authorsContainer.querySelector('.author-row');
                const newAuthorRow = firstAuthorRow.cloneNode(true);

                // Clear inputs in cloned row
                newAuthorRow.querySelectorAll('input').forEach(input => input.value = '');

                authorsContainer.appendChild(newAuthorRow);
                updateButtonsVisibility();
            }

            if (target.classList.contains('btn-remove-author')) {
                const authorRows = authorsContainer.querySelectorAll('.author-row');
                if (authorRows.length > 1) {
                    // Remove the row containing this button
                    const rowToRemove = target.closest('.author-row');
                    rowToRemove.remove();
                    updateButtonsVisibility();
                }
            }
        });

        // Initialize button visibility on page load
        updateButtonsVisibility();

        const doiInput = document.getElementById('doi');
        if (doiInput && !doiInput.value) {
            // Generate DOI: 10.1234/journal.{year}.{random_number}
            const year = new Date().getFullYear();
            const randomNum = Math.floor(Math.random() * 9000) + 1000; // random 4-digit number
            doiInput.value = `10.1234/b.c.${year}.${randomNum}`;
        }

        document.getElementById('collaborationCheckbox').addEventListener('change', function () {
            const collaborationFields = document.getElementById('collaborationFields');
            collaborationFields.style.display = this.checked ? 'block' : 'none';
        });

        form.addEventListener("submit", function (e) {
            e.preventDefault();

            const fieldNames = {
                publication_date: "{{ __('Publication Date') }}",
                chapter_title: "{{ __('Title of the chapter') }}",
                page: "{{ __('Page Number') }}",
                doi: "{{ __('DOI') }}",
                author_foreign: "{{ __('Foreign Author Name') }}",
                affiliation_foreign: "{{ __('Foreign Affiliation') }}",
                author_indian: "{{ __('Indian Author Name') }}",
                affiliation_indian: "{{ __('Indian Affiliation') }}",
                percentile: "{{ __('Percentile') }}"
            };

            let hasError = false;

            // Validate all author first names
            const authorFirstNames = document.querySelectorAll('input[name="author_first_names[]"]');
            const errorAuthor = document.getElementById('error-author');
            authorFirstNames.forEach((input, index) => {
                if (!input.value.trim()) {
                    errorAuthor.textContent = "{{ __('Please enter the first name for author') }} " + (index + 1);
                    hasError = true;
                }
            });
            if (!hasError) {
                errorAuthor.textContent = "";
            }

            const collaborationChecked = document.getElementById('collaborationCheckbox').checked;

            Object.keys(fieldNames).forEach((id) => {
                const input = document.getElementById(id);
                const error = document.getElementById(`error-${id}`);
                if (!input || !error) return;

                const isCollabField = [
                    'author_foreign',
                    'affiliation_foreign',
                    'author_indian',
                    'affiliation_indian'
                ].includes(id);

                if (!collaborationChecked && isCollabField) return;

               input.addEventListener("input", () => {
                    if (input.value.trim()) {
                        error.textContent = "";
                    }
                });

                // Only 'percentile' is optional
                const isRequired = !isCollabField && id !== 'percentile';
                if (isRequired && !input.value.trim()) {
                    error.textContent = "{{ __('Please Enter The') }} " + fieldNames[id];
                    hasError = true;
                } else {
                    error.textContent = "";
                }
            });

            if (hasError) return;

            const formData = new FormData(form);
            fetch("{{ route('bookChapter.submit') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                Swal.fire({
                    title: "{{ __('Success!') }}",
                    text: data.message,
                    icon: "success",
                    confirmButtonText: "{{ __('Go to Published List') }}"
                }).then(() => {
                    window.location.href = "{{ route('admin.bookChapters.published') }}";
                });
                form.reset();
                form.style.display = 'none';
                chapterSelect.value = '';
                resetAuthorRows();
                titleInput.value = '';
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire(
                    "{{ __('Error') }}",
                    "{{ __('Something went wrong!') }} " + error.message,
                    "error"
                );
            });
        });
    });
</script>
