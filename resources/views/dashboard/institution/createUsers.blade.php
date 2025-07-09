@extends('layouts.institution')

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-lg-11">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card">
                <h5 class="card-title mb-3 fw-bold">{{ __('Create User') }}</h5>

                <form id="userForm" action="{{ route('institution.users.store') }}" method="POST">
                    @csrf
                    {{-- <input type="hidden" name="role" id="role" value=""> --}}
                    <input type="hidden" id="editUserId" name="editUserId">
                    {{-- <input type="hidden"  id="formMethod" value="POST"> --}}
                    <div class="col-12 mb-2">
                        <label for="name">{{ __('User Name') }}</label>
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
                   <div class="col-12 mb-2" id="roleField">
                        <label for="role">{{ __('Select Role') }}</label>
                        <select class="form-control w-100 @error('role') is-invalid @enderror" name="role" id="role">
                            <option value="">{{ __('--Select a role--') }}</option>
                            <option value="researcher" {{ old('role') == 'researcher' ? 'selected' : '' }}>{{ __('Researcher') }}</option>
                            <option value="reviewer" {{ old('role') == 'reviewer' ? 'selected' : '' }}>{{ __('Reviewer') }}</option>
                        </select>
                        <div id="error-role" class="text-danger"></div>
                    </div>

                    <div class="col-12 mb-2" id="departmentField">
                        <label for="dept">{{ __('Department') }}</label>
                        <select class="form-control w-100 @error('dept') is-invalid @enderror" name="department_id" id="dept" value="{{ old('dept') }}">
                            <option value="">{{ __("Select a Department") }}</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}" {{ old('dept') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        <div id="error-dept" class="text-danger"></div>
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

    <div class="col-lg-11">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card">
                <h5 class="card-title mb-3">{{ __('View Users') }}</h5>
                <div class="table-responsive">
                    <table class="table table table-bordered border-dark-subtle table-hover fs-6" id="userTable">
                        <thead class="custom-header">
                            <tr>
                                <th>{{ __('Id') }}</th>
                                <th>{{ __('User Name') }}</th>
                                <th>{{ __('Email Id') }}</th>
                                <th>{{ __('Mobile Number') }}</th>
                                <th>{{ __('Role') }}</th>
                                <th>{{ __('Department') }}</th>
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
        const roleField = document.getElementById("roleField");
        let users = []; 

        fetchUsers();

        userForm.addEventListener("submit", async function (e) {
        e.preventDefault();

        const fieldNames = {
    name: "{{ __('Name') }}",
    mobile_number: "{{ __('Mobile Number') }}",
    email: "{{ __('Email ID') }}",
    password: "{{ __('Password') }}",
    role: "{{ __('Role') }}",
    dept: "{{ __('Department') }}"
};
        const name = document.getElementById("name").value;
        const mobile_number = document.getElementById("mobile_number").value;
        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;
        const role = document.getElementById("role").value;
        const dept = document.getElementById("dept").value;

        // Client-side validation
        let hasError = false;
        const fields = ["name", "mobile_number", "email",'role','dept'];
        if (!editUserIdInput.value) {
            // Only validate role, dept, and password for create
            fields.push("role");
            if (role === "researcher") fields.push("dept");
            fields.push("password");
        } else if (role === "researcher") {
            if (roleSelect.value === "researcher") {
                departmentField.style.display = "block";
            } else {
                departmentField.style.display = "none";
            }
            // For updates, only validate dept if role is researcher
            fields.push("dept");
        }

        fields.forEach((id) => {
            const input = document.getElementById(id);
            const error = document.getElementById(`error-${id}`);
            input.addEventListener("input", () => {
                if (input.value.trim()) {
                    error.textContent = "";
                }
            });
            if (!input.value.trim()) {
                error.textContent = '{{ __('Please Enter The')}} ' + fieldNames[id];
                hasError = true;
            } else {
                error.textContent = "";
            }
        });

        if (!editUserIdInput.value && password.length < 6) {
            document.getElementById("error-password").textContent = "Password must be at least 6 characters long";
            hasError = true;
        }

        if (hasError) return;

        const formData = new FormData();
        formData.append("name", name);
        formData.append("mobile_number", mobile_number);
        formData.append("email", email);
        if (!editUserIdInput.value) {
            // Only append role and password for create
            formData.append("role", role);
            if (password) formData.append("password", password);
        }
        
            formData.append("department_id", dept);
        

        const isEditing = editUserIdInput.value !== "";
        let url = "{{ route('institution.users.store') }}";
        let method = "POST";

        if (isEditing) {
            url = "{{ route('institution.user.update', ':id') }}".replace(':id', editUserIdInput.value);
            formData.append("_method", "POST"); // Correct method spoofing for update
        }

        // Log request details for debugging
        console.log("Submitting to:", url);
        console.log("FormData:", Object.fromEntries(formData));

        try {
            const response = await fetch(url, {
                method: "POST", // Always POST due to Laravel's method spoofing
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
                showPopup(result.message || "User saved successfully");
                userForm.reset();
                editUserIdInput.value = "";
                passwordField.style.display = "block";
                roleField.style.display = "block";
                departmentField.style.display = role === "researcher" ? "block" : "none";
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
                document.getElementById("role").value = user.role;
                document.getElementById("dept").value = user.department_id || "";
                editUserIdInput.value = userId;
                passwordField.style.display = "none";
                roleField.style.display = "none";
            }
        }   

        if (target.classList.contains("del-btn")) {
            showPopup("This will delete the user permanently!", true, async () => {
                const url = "{{ route('institution.user.destroy', ':id') }}".replace(':id', userId);
                console.log("Deleting user at:", url);

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
                        showPopup("User deleted successfully");
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
            const response = await fetch("{{ route('institution.users.list') }}", {
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
                <td>${user.role.charAt(0).toUpperCase() + user.role.slice(1)}</td>
                <td>${user.department_name }</td>
                <td>
                    <button class="btn btn-sm btn-success edit-btn mb-1">{{ __('Edit')}}</i></button>
                    <button class="btn btn-sm btn-danger del-btn mb-1">{{ __('Delete')}}</i></button>
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