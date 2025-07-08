@extends('layouts.admin')

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-lg-11">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body book-card">
                <h5 class="card-title mb-3 fw-bold">{{ __('Create Books') }}</h5>
                <form id="bookForm" action="{{ route('admin.books.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="editbookId" name="editbookId">
                    <input type="hidden" id="formMethod" name="_method" value="POST">
                    <div class="col-12 mb-2">
                        <label for="title">{{ __('Book Title') }}</label>
                        <input type="text" class="form-control w-100 @error('title') is-invalid @enderror" name="title" id="title" value="{{ old('title') }}">
                        <div id="error-title" class="text-danger">@error('title') {{ $message }} @enderror</div>
                    </div>
                    <div class="col-12 mb-2">
                        <label for="isbn">{{ __('Enter the ISBN') }}</label>
                        <input type="text" class="form-control w-100 @error('isbn') is-invalid @enderror" name="isbn" id="isbn" value="{{ old('isbn') }}">
                        <div id="error-isbn" class="text-danger">@error('isbn') {{ $message }} @enderror</div>
                    </div>
                    <div class="col-12 mb-2">
                        <label for="doi">{{ __('Enter the Book DOI (Optional)') }}</label>
                        <input type="text" class="form-control w-100 @error('doi') is-invalid @enderror" name="doi" id="doi" value="{{ old('doi') }}">
                        <div id="error-doi" class="text-danger">@error('doi') {{ $message }} @enderror</div>
                    </div>
                    <div class="col-12 mb-2">
                        <label for="edition">{{ __('Enter the Book Edition') }}</label>
                        <input type="number" class="form-control w-100 @error('edition') is-invalid @enderror" name="edition" id="edition" value="{{ old('edition') }}">
                        <div id="error-edition" class="text-danger">@error('edition') {{ $message }} @enderror</div>
                    </div>
                    <div class="col-12 mb-2">
                        <label for="genre">{{ __('Enter the Book Genre') }}</label>
                        <input type="text" class="form-control w-100 @error('genre') is-invalid @enderror" name="genre" id="genre" value="{{ old('genre') }}">
                        <div id="error-genre" class="text-danger">@error('genre') {{ $message }} @enderror</div>
                    </div>
                    <div class="col-12 mb-2">
                        <label for="publisher">{{ __('Enter the Book Publisher') }}</label>
                        <input type="text" class="form-control w-100 @error('publisher') is-invalid @enderror" name="publisher" id="publisher" value="{{ old('publisher') }}">
                        <div id="error-publisher" class="text-danger">@error('publisher') {{ $message }} @enderror</div>
                    </div>
                    <div class="col-12 mb-2">
                        <label for="publication_date">{{ __('Enter the Book Publication Date') }}</label>
                        <input type="date" class="form-control w-100 @error('publication_date') is-invalid @enderror" name="publication_date" id="publication_date" value="{{ old('publication_date') }}">
                        <div id="error-publication_date" class="text-danger">@error('publication_date') {{ $message }} @enderror</div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">{{ __('Create Book') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-11">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body book-card">
                <h5 class="card-title mb-3 fw-bold">{{ __('View Books') }}</h5>
                <div class="table-responsive">
                    <table class="table table-bordered border-dark-subtle table-hover fs-6" id="bookTable">
                        <thead class="custom-header">
                            <tr>
                                <th>#</th>
                                <th>{{ __('Book Title')}}</th>
                                <th>{{ __('ISBN')}}</th>
                                <th>{{ __('DOI')}}</th>
                                <th>{{ __('Edition')}}</th>
                                <th>{{ __('Genre')}}</th>
                                <th>{{ __('Publisher')}}</th>
                                <th>{{ __('Publication Date')}}</th>
                                <th>{{ __('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let books = [];
const bookTable = document.getElementById("bookTable");
const bookForm = document.getElementById("bookForm");
const editbookIdInput = document.getElementById("editbookId");
const formMethod = document.getElementById("formMethod");

document.addEventListener("DOMContentLoaded", function () {
    fetchBooks();

    bookForm.addEventListener("submit", async function (e) {
        e.preventDefault();

        const title = document.getElementById("title").value;
        const isbn = document.getElementById("isbn").value;
        const doi = document.getElementById("doi").value;
        const edition = document.getElementById("edition").value;
        const genre = document.getElementById("genre").value;
        const publisher = document.getElementById("publisher").value;
        const publication_date = document.getElementById("publication_date").value;

        // Translation mapping for field names
        const fieldNames = {
            title: "{{ __('Book Title') }}",
            isbn: "{{ __('Enter the ISBN') }}",
            edition: "{{ __('Enter the Book Edition') }}",
            genre: "{{ __('Enter the Book Genre') }}",
            publisher: "{!! __('Enter the Book Publisher') !!}",
            publication_date: "{{ __('Enter the Book Publication Date') }}"
        };

        let hasError = false;
        const fields = ["title", "isbn", "edition", "genre", "publisher", "publication_date"];
        fields.forEach((id) => {
            const input = document.getElementById(id);
            const error = document.getElementById(`error-${id}`);
            input.addEventListener("input", () => {
                if (input.value.trim()) {
                    error.textContent = "";
                }
            });
            if (!input.value.trim()) {
                error.textContent = "{{ __('Please Enter The') }} " + fieldNames[id];
                hasError = true;
            } else {
                error.textContent = "";
            }
        });

        if (hasError) return;

        const formData = new FormData();
        formData.append("title", title);
        formData.append("isbn", isbn);
        formData.append("doi", doi);
        formData.append("edition", edition);
        formData.append("genre", genre);
        formData.append("publisher", publisher);
        formData.append("publication_date", publication_date);

        const isEditing = editbookIdInput.value !== "";
        let url = bookForm.action;
        if (isEditing) {
            url = "{{ route('admin.book.update', ':id') }}".replace(':id', editbookIdInput.value);
            formData.append("_method", "POST");
            formMethod.value = "PUT";
        } else {
            formMethod.value = "POST";
        }

        try {
            const response = await fetch(url, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json",
                },
                body: formData,
            });

            const result = await response.json();
            if (response.ok) {
                showPopup(result.message || "{{ __('Book saved successfully') }}");
                bookForm.reset();
                editbookIdInput.value = "";
                formMethod.value = "POST";
                fetchBooks();
            } else {
                if (result.errors) {
                    Object.keys(result.errors).forEach((key) => {
                        document.getElementById(`error-${key}`).textContent = result.errors[key][0];
                    });
                }
                showPopup("{{ __('Failed to save book:') }} " + (result.message || response.statusText));
            }
        } catch (error) {
            console.error("Error during form submission:", error);
            showPopup("{{ __('Something went wrong:') }} " + error.message);
        }
    });

    if (bookTable) {
        bookTable.addEventListener("click", async function (e) {
            const target = e.target.closest("button");
            if (!target) return;

            const row = target.closest("tr");
            const bookId = row.dataset.bookId;

            if (target.classList.contains("edit-btn")) {
                const book = books.find(b => b.id == bookId);
                if (book) {
                    document.getElementById("title").value = book.title;
                    document.getElementById("isbn").value = book.isbn;
                    document.getElementById("doi").value = book.doi || '';
                    document.getElementById("edition").value = book.edition;
                    document.getElementById("genre").value = book.genre;
                    document.getElementById("publisher").value = book.publisher;
                    document.getElementById("publication_date").value = book.publication_date;
                    editbookIdInput.value = bookId;
                }
            }

            if (target.classList.contains("del-btn")) {
                showPopup("{{ __('This will delete the book permanently!') }}", true, async () => {
                    const url = "{{ route('admin.book.destroy', ':id') }}".replace(':id', bookId);
                    try {
                        const response = await fetch(url, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                                "Accept": "application/json",
                            },
                            body: JSON.stringify({ _method: "POST" }),
                        });

                        const result = await response.json();
                        if (response.ok) {
                            showPopup("{{ __('Book deleted successfully') }}");
                            fetchBooks();
                        } else {
                            showPopup("{{ __('Failed to delete book:') }} " + (result.message || response.statusText));
                        }
                    } catch (error) {
                        console.error("Error during deletion:", error);
                        showPopup("{{ __('Something went wrong:') }} " + error.message);
                    }
                });
            }
        });
    }
});

async function fetchBooks() {
    try {
        const response = await fetch("{{ route('book.list') }}", {
            method: "GET",
            headers: {
                "Accept": "application/json",
            },
        });
        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`HTTP error: ${response.status} - ${errorText}`);
        }
        books = await response.json();
        renderBooks();
    } catch (error) {
        console.error("Error fetching books:", error);
        showPopup("{{ __('Failed to load books:') }} " + error.message);
    }
}

function renderBooks() {
    if (!bookTable) {
        console.log("bookTable not found in DOM");
        return;
    }
    const tbody = bookTable.querySelector('tbody');
    tbody.innerHTML = "";
    if (books.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" class="text-center">{{ __('No Books Published') }}</td></tr>`;
        return;
    }
    books.forEach((book, index) => {
        const row = tbody.insertRow();
        row.setAttribute("data-book-id", book.id);
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${book.title}</td>
            <td>${book.isbn}</td>
            <td>${book.doi || 'N/A'}</td>
            <td>${book.edition}</td>
            <td>${book.genre}</td>
            <td>${book.publisher}</td>
            <td>${book.publication_date}</td>
            <td>
                <button class="btn btn-sm btn-success edit-btn mb-1">{{ __('Edit') }}</button>
                <button class="btn btn-sm btn-danger del-btn mb-1">{{ __('Delete') }}</button>
            </td>
        `;
    });
}

function showPopup(message, isConfirm = false, onConfirm = null) {
    if (isConfirm) {
        Swal.fire({
            title: "{{ __('Are you sure?') }}",
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: "{{ __('Yes, delete it') }}",
            cancelButtonText: "{{ __('Cancel') }}"
        }).then((result) => {
            if (result.isConfirmed && typeof onConfirm === 'function') {
                onConfirm();
            }
        });
    } else {
        Swal.fire({
            title: message.includes('Failed') ? "{{ __('Error') }}" : "{{ __('Success!') }}",
            text: message,
            icon: message.includes('Failed') ? 'error' : 'success',
            confirmButtonText: "{{ __('OK') }}"
        });
    }
}
</script>
@endpush