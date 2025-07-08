@extends('layouts.department')

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-lg-11">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card">
                <h5 class="card-title mb-3 fw-bold">{{ __('Create User') }}</h5>

                <form id="userForm">
                    @csrf
                    <input type="hidden" id="editUserId" name="editUserId">
                    
                    <div class="col-12 mb-2">
                        <label for="name">{{ __('User Name') }}</label>
                        <input type="text" class="form-control w-100" name="name" id="name">
                        <div id="error-name" class="text-danger"></div>
                    </div>

                    <div class="col-12 mb-2">
                        <label for="email">{{ __('Email ID') }}</label>
                        <input type="email" class="form-control w-100" name="email" id="email">
                        <div id="error-email" class="text-danger"></div>
                    </div>

                    <div class="col-12 mb-2">
                        <label for="mobile_number">{{ __('Mobile Number') }}</label>
                        <input type="text" class="form-control w-100" name="mobile_number" id="mobile_number" pattern="[789][0-9]{9}" title="Must be 10 digits starting with 9, 8 or 7">
                        <div id="error-mobile_number" class="text-danger"></div>
                    </div>

                    <div class="col-12 mb-2" id="roleField">
                        <label for="role">{{ __('Select Role') }}</label>
                        <select class="form-control w-100" name="role" id="role">
                            <option value="">{{ __('--Select a role--') }}</option>
                            <option value="researcher">{{ __('Researcher') }}</option>
                            <option value="reviewer">{{ __('Reviewer') }}</option>
                        </select>
                        <div id="error-role" class="text-danger"></div>
                    </div>

                    <div class="col-12 mb-2" id="passwordField">
                        <label for="password">{{ __('Password') }}</label>
                        <input type="password" class="form-control w-100" name="password" id="password">
                        <div id="error-password" class="text-danger"></div>
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
                <h5 class="card-title mb-3">{{ __('View Users') }}</h5>
                <div class="table-responsive">
                    <table class="table table-bordered border-dark-subtle table-hover fs-6" id="userTable">
                        <thead class="custom-header">
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('User Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Mobile') }}</th>
                                <th>{{ __('Role') }}</th>
                                <th>{{ __('Actions') }}</th>
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
document.addEventListener("DOMContentLoaded", () => {
    const userForm = document.getElementById("userForm");
    const editUserIdInput = document.getElementById("editUserId");
    const roleField = document.getElementById("roleField");
    const passwordField = document.getElementById("passwordField");
    const userTableBody = document.querySelector("#userTable tbody");

    let users = [];

    fetchUsers();

    userForm.addEventListener("submit", async function (e) {
        e.preventDefault();

        const name = document.getElementById("name").value.trim();
        const email = document.getElementById("email").value.trim();
        const mobile = document.getElementById("mobile_number").value.trim();
        const role = document.getElementById("role").value;
        const password = document.getElementById("password").value;

        const fieldNames = {
            name: "{{ __('User Name') }}",
            email: "{{ __('Email ID') }}",
            mobile_number: "{{ __('Mobile Number') }}",
            role: "{{ __('Role') }}",
            password: "{{ __('Password') }}"
        };

        const errors = {
            name: !name,
            email: !email,
            mobile_number: !mobile,
            role: !editUserIdInput.value && !role,
            password: !editUserIdInput.value && password.length < 6,
        };

        let hasError = false;
        for (let field in errors) {
            const errorElem = document.getElementById(`error-${field}`);
            if (errors[field]) {
                if (field === 'password' && !editUserIdInput.value && password.length < 6) {
                    errorElem.textContent = "{{ __('Password must be at least 6 characters long') }}";
                } else {
                    errorElem.textContent = "{{ __('Please Enter The') }} " + fieldNames[field];
                    errorElem.textContent = "{{ __('Please Enter The') }} " + fieldNames[field];
                }
                hasError = true;
            } else {
                errorElem.textContent = "";
            }
        }
        if (hasError) return;

        const formData = new FormData();
        formData.append("name", name);
        formData.append("email", email);
        formData.append("mobile_number", mobile);
        if (!editUserIdInput.value) {
            formData.append("role", role);
            formData.append("password", password);
        }

        const isEditing = !!editUserIdInput.value;
        const url = isEditing
            ? `{{ route('department.user.update', ':id') }}`.replace(':id', editUserIdInput.value)
            : `{{ route('department.users.store') }}`;

        if (isEditing) {
            formData.append("_method", "POST");
        }

        try {
            const response = await fetch(url, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json"
                },
                body: formData
            });

            const result = await response.json();

            if (response.ok) {
                showPopup(result.message || "User saved");
                userForm.reset();
                editUserIdInput.value = "";
                passwordField.style.display = "block";
                roleField.style.display = "block";
                fetchUsers();
            } else {
                for (const key in result.errors) {
                    const elem = document.getElementById(`error-${key}`);
                    if (elem) elem.textContent = result.errors[key][0];
                }
            }
        } catch (err) {
            console.error("Form error", err);
            showPopup("Something went wrong");
        }
    });

    userTableBody.addEventListener("click", async (e) => {
        const btn = e.target.closest("button");
        if (!btn) return;

        const row = btn.closest("tr");
        const userId = row.dataset.userId;
        const user = users.find(u => u.id == userId);

        if (btn.classList.contains("edit-btn")) {
            if (user) {
                document.getElementById("name").value = user.name;
                document.getElementById("email").value = user.email;
                document.getElementById("mobile_number").value = user.mobile_number;
                document.getElementById("role").value = user.role;
                passwordField.style.display = "none";
                roleField.style.display = "none";
                editUserIdInput.value = user.id;
            }
        }

        if (btn.classList.contains("del-btn")) {
            showPopup("Confirm deletion?", true, async () => {
                const url = `{{ route('department.user.destroy', ':id') }}`.replace(":id", userId);
                const response = await fetch(url, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Accept": "application/json",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ _method: "POST" })
                });

                const result = await response.json();
                if (response.ok) {
                    showPopup("User deleted");
                    fetchUsers();
                } else {
                    showPopup("Error deleting user");
                }
            });
        }
    });

    async function fetchUsers() {
        try {
            const response = await fetch(`{{ route('department.users.list') }}`);
            users = await response.json();
            renderUsers();
        } catch (e) {
            console.error("Fetch error", e);
        }
    }

    function renderUsers() {
        userTableBody.innerHTML = "";
        users.forEach((u, i) => {
            const row = userTableBody.insertRow();
            row.setAttribute("data-user-id", u.id);
            row.innerHTML = `
                <td>${i + 1}</td>
                <td>${u.name}</td>
                <td>${u.email}</td>
                <td>${u.mobile_number}</td>
                <td>${u.role}</td>
                <td>
                    <button class="btn btn-success btn-sm edit-btn">{{ __('Edit')}}</button>
                    <button class="btn btn-danger btn-sm del-btn">{{ __('Delete')}}</button>
                </td>
            `;
        });
    }

    function showPopup(message, confirm = false, onConfirm = null) {
        if (confirm) {
            Swal.fire({
                title: '{{ __("Are you sure?") }}',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '{{ __("Yes, delete it") }}',
                cancelButtonText: '{{ __("No") }}'
            }).then((result) => {
                if (result.isConfirmed && typeof onConfirm === "function") {
                    onConfirm();
                }
            });
        } else {
            Swal.fire({
                title: '{{ __("Success") }}',
                text: message,
                icon: 'success',
                confirmButtonText: '{{ __("OK") }}'
            });
        }
    }
});
</script>
@endpush
