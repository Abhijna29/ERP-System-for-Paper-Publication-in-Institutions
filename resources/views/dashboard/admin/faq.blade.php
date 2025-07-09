@extends('layouts.admin')

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-lg-11">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card">
                <h5 class="card-title mb-3 fw-bold">{{ __('Create FAQ') }}</h5>
                <form id="form">
                    <input type="hidden" id="editIndex" value="">
                    <div class="col-12 mb-3">
                        <label for="title">{{ __('Title')}}</label>
                        <input type="text" class="form-control w-100" name="title" id="title">
                         <div id="error-title" class="text-danger"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="description">{{ __('Description')}}</label>
                        <textarea type="text" class="form-control w-100" name="description" id="description"></textarea>
                         <div id="error-description" class="text-danger"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="role">{{ __('Visible To (Role)') }}</label>
                        <select class="form-select" id="role" name="role">
                            <option value="">{{ __('All Roles') }}</option>
                            <option value="researcher">{{ __('Researcher')}}</option>
                            <option value="reviewer">{{ __('Reviewer')}}</option>
                            <option value="institution">{{ __('Institution')}}</option>
                            <option value="department">{{ __('Department')}}</option>
                        </select>
                        <div id="error-role" class="text-danger"></div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">{{ __('Submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-11">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card">
                <h5 class="card-title mb-3 fw-bold">{{ __('View FAQ') }}</h5>
                <div class="table-responsive">
                    <table class="table table-bordered border-dark-subtle table-hover fs-6" id="table">
                        <thead class="custom-header">
                            <tr>
                                <th>{{ __('Id')}}</th>
                                <th>{{ __('Title')}}</th>
                                <th>{{ __('Description')}}</th>
                                <th>{{ __('Visible To (Role)')}}</th>
                                <th>{{ __('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let editingId = null;

function fetchFAQs() {
    fetch("{{ route('admin.faq.fetch') }}")
    .then(res => {
        if (!res.ok) throw new Error('Failed to fetch FAQs');
        return res.json();
    })
    .then(data => {
        const tableBody = document.querySelector("#table tbody");
        tableBody.innerHTML = "";
        data.forEach((faq, index) => {
            tableBody.innerHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${faq.title}</td>
                    <td>${faq.description}</td>
                    <td>${faq.role ? faq.role.charAt(0).toUpperCase() + faq.role.slice(1) : 'All'}</td>

                    <td>
                        <button class="btn btn-sm btn-success edit-btn mb-1" data-id="${faq.id}" data-title="${faq.title}" data-description="${faq.description}" data-role="${faq.role || ''}">{{ __('Edit')}}</button>
                        <button class="btn btn-sm btn-danger del-btn" data-id="${faq.id}">{{ __('Delete')}}</button>
                    </td>
                </tr>`;
        });
    })
    .catch(err => {
        console.error("Fetch FAQs failed:", err);
    });
}

document.addEventListener("DOMContentLoaded", function () {
    fetchFAQs();

    document.getElementById("form").addEventListener("submit", function (e) {
        e.preventDefault();
        const title = document.getElementById("title").value.trim();
        const description = document.getElementById("description").value.trim();
        const role = document.getElementById("role").value;
        const errorTitle = document.getElementById("error-title");
        const errorDesc = document.getElementById("error-description");

        errorTitle.textContent = title ? "" : "{{ __('Please enter the title') }}";
errorDesc.textContent = description ? "" : "{{ __('Please enter the description') }}";

        if (!title || !description) return;

        const method = editingId ? 'PUT' : 'POST';
        const url = editingId
            ? `{{ url('/admin/faqs') }}/${editingId}`
            : `{{ route('admin.faq.store') }}`;

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                title: title,
                description: description,
                role:role
            })
        })
        .then(res => res.json())
        .then(() => {
            Swal.fire("Success!", `FAQ ${editingId ? 'updated' : 'created'} successfully!`, "success");
            editingId = null;
            document.getElementById("form").reset();
            fetchFAQs();
        });
    });

    document.querySelector("#table").addEventListener("click", function (e) {
        const target = e.target.closest("button");
        if (!target) return;

        const id = target.dataset.id;

        if (target.classList.contains("edit-btn")) {
            editingId = id;
            document.getElementById("title").value = target.dataset.title;
            document.getElementById("description").value = target.dataset.description;
            document.getElementById("role").value = target.dataset.role || '';

        }

        if (target.classList.contains("del-btn")) {
            Swal.fire({
                title: "Are you sure?",
                text: "This will delete the FAQ permanently!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('/admin/faqs') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(res => res.json())
                    .then(() => {
                        Swal.fire("Deleted!", "FAQ has been deleted.", "success");
                        fetchFAQs();
                    });
                }
            });
        }
    });
});
</script>
@endpush

