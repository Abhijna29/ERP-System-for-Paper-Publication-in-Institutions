@extends('layouts.institution')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card">
                <h5 class="card-title mb-3 fw-bold">{{ __('Create Department') }}</h5>

                <form id="userForm" action="{{ route('institution.departments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="role" id="role" value="department">
                    <input type="hidden" id="editUserId" name="editUserId">
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="col-12 mb-2">
                        <label for="name">{{ __('Department Name') }}</label>
                        <input type="text" class="form-control w-100 @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name') }}">
                        <div id="error-name" class="text-danger"></div>
                    </div>
                    <div class="col-12 mb-2">
                        <label for="email">{{ __('Email ID') }}</label>
                        <input type="email" class="form-control w-100 @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('email') }}">
                        <div id="error-email" class="text-danger"></div>
                    </div>
                    <div class="col-12 mb-2">
                        <label for="mobile_number">{{ __('Mobile Number') }}</label>
                        <input type="text" class="form-control w-100 @error('mobile_number') is-invalid @enderror" name="mobile_number" id="mobile_number" value="{{ old('mobile_number') }}" pattern="[789][0-9]{9}" title="Mobile number must start with 9, 8, or 7 and be 10 digits long"  placeholder="Enter a 10-digit phone number">
                        <div id="error-mobile_number" class="text-danger"></div>
                    </div>
                    <div class="col-12 mb-2" id="passwordField">
                        <label for="password">{{ __('Password') }}</label>
                        <input type="password" class="form-control w-100 @error('password') is-invalid @enderror" name="password" id="password">
                        <div id="error-password" class="text-danger"></div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">{{ __('Submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card">
                <h5 class="card-title mb-3">{{ __('View Department') }}</h5>
                <div class="table-responsive">
                    <table class="table table table-bordered border-dark-subtle table-hover fs-6" id="userTable">
                        <thead class="custom-header">
                            <tr>
                                <th>{{ __('Id') }}</th>
                                <th>{{ __('Department Name') }}</th>
                                <th>{{ __('Email Id') }}</th>
                                <th>{{ __('Mobile Number') }}</th>
                                <th>{{ __('Action') }}</th>
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
document.addEventListener("DOMContentLoaded", function () {
    const userForm = document.getElementById("userForm");
    const userTableBody = document.querySelector("#userTable tbody");
    const editUserIdInput = document.getElementById("editUserId");
    const formMethod = document.getElementById("formMethod");
    const passwordField = document.getElementById("passwordField");
    let users = [];

    fetchUsers();

    userForm.addEventListener("submit", async function (e) {
        e.preventDefault();

        const fieldNames = {
    name: "{{ __('Department Name') }}",
    mobile_number: "{{ __('Mobile Number') }}",
    email: "{{ __('Email ID') }}",
    password: "{{ __('Password') }}"
};
        const name = document.getElementById("name").value;
        const mobile_number = document.getElementById("mobile_number").value;
        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;

        // Client-side validation
        let hasError = false;
        const fields = ["name", "mobile_number", "email"];
        if (!editUserIdInput.value) fields.push("password");

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

        if (!editUserIdInput.value && password.length < 6) {
        document.getElementById("error-password").textContent = "{{ __('Password must be at least 6 characters long') }}";
        hasError = true;
        }

        if (hasError) return;

        const formData = new FormData();
        formData.append("name", name);
        formData.append("mobile_number", mobile_number);
        formData.append("email", email);
        formData.append("role", "department");
        if (password) formData.append("password", password);

        const isEditing = editUserIdInput.value !== "";
        let url = userForm.action;
        if (isEditing) {
            url = "{{ route('department.update', ':id') }}".replace(':id', editUserIdInput.value);
            formData.append("_method", "POST");
            formMethod.value = "PUT";
        } else {
            formMethod.value = "POST";
        }

        // Log request details for debugging
        console.log("Submitting to:", url);
        console.log("FormData:", Object.fromEntries(formData));

        try {
            const response = await fetch(url, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json",
                },
                body: formData,
            });

            let result;
            try {
                result = await response.json();
            } catch (jsonError) {
                const text = await response.text();
                throw new Error(`Invalid JSON response: ${text.slice(0, 100)}...`);
            }

            if (response.ok) {
                showPopup(result.message || "Department saved successfully");
                userForm.reset();
                editUserIdInput.value = "";
                passwordField.style.display = "block";
                formMethod.value = "POST";
                fetchUsers();
            } else {
                if (result.errors) {
                    Object.keys(result.errors).forEach((key) => {
                        document.getElementById(`error-${key}`).textContent = result.errors[key][0];
                    });
                }
                showPopup(`Failed to save user: ${result.message || response.statusText} (Status: ${response.status})`);
            }
        } catch (error) {
            console.error("Error during form submission:", error, error.stack);
            showPopup(`Something went wrong: ${error.message}`);
        }
    });

    // Table button click handler
    userTableBody.addEventListener("click", async function (e) {
        const target = e.target.closest("button");
        if (!target) return;

        const row = target.closest("tr");
        const userId = row.dataset.userId;

        if (target.classList.contains("edit-btn")) {
            const user = users.find(u => u.id == userId);
            if (user) {
                document.getElementById("name").value = user.name;
                document.getElementById("mobile_number").value = user.mobile_number;
                document.getElementById("email").value = user.email;
                editUserIdInput.value = userId;
                passwordField.style.display = "none";
            }
        }

        if (target.classList.contains("del-btn")) {
            showPopup("This will delete the department permanently!", true, async () => {
                const url = "{{ route('department.destroy', ':id') }}".replace(':id', userId);

                try {
                    const response = await fetch(url, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                            "Accept": "application/json",
                        },
                        body: JSON.stringify({ _method: "POST" }),
                    });

                    let result;
                    try {
                        result = await response.json();
                    } catch (jsonError) {
                        const text = await response.text();
                        throw new Error(`Invalid JSON response: ${text.slice(0, 100)}...`);
                    }

                    if (response.ok) {
                        showPopup("Department deleted successfully");
                        fetchUsers();
                    } else {
                        showPopup(`Failed to delete user: ${result.message || response.statusText} (Status: ${response.status})`);
                    }
                } catch (error) {
                    console.error("Error during deletion:", error, error.stack);
                    showPopup(`Something went wrong: ${error.message}`);
                }
            });
        }
    });

    // Fetch users from the database
    async function fetchUsers() {
        try {
            const response = await fetch("{{ route('departments.list') }}", {
                headers: {
                    "Accept": "application/json",
                },
            });
            if (!response.ok) throw new Error(`HTTP error: ${response.status} ${response.statusText}`);
            users = await response.json();
            renderUsers();
        } catch (error) {
            console.error("Error fetching users:", error, error.stack);
            // showPopup(`Failed to load users: ${error.message}`);
        }
    }

    // Render users in the table
    function renderUsers() {
        userTableBody.innerHTML = "";
        users.forEach((user, index) => {
            const row = userTableBody.insertRow();
            row.setAttribute("data-user-id", user.id);
            row.innerHTML = `
                <td>${index + 1}</td>
                <td>${user.name}</td>
                <td>${user.email}</td>
                <td>${user.mobile_number}</td>
                <td>
                    <button class="btn btn-sm btn-success edit-btn mb-1">{{ __('Edit')}}</i></button>
                    <button class="btn btn-sm btn-danger del-btn mb-1">{{ __('Delete')}}</button>
                </td>
            `;
        });
    }

    // Show notification popup
    function showPopup(message, isConfirm = false, onConfirm = null) {
    if (isConfirm) {
        Swal.fire({
            title: 'Are you sure?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed && typeof onConfirm === 'function') {
                onConfirm();
            }
        });
    } else {
        Swal.fire({
            title: 'Success',
            text: message,
            icon: 'success',
            confirmButtonText: 'OK'
        });
    }
    }
});
</script>
@endpush