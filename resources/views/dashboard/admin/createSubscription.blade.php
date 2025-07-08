@extends('layouts.admin')

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-11">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card">
                <h5 class="card-title mb-3 fw-bold">{{ __('Create Subscription Plans') }}</h5>

                <form id="subscriptionForm" method="POST" action="{{ route('admin.subscription.store') }}">
                    @csrf
                    @method('POST')
                    <input type="hidden" id="planId" name="id" value="">
                    <div class="col-12 mb-3">
                        <label for="name">{{ __('Plan Name') }}</label>
                        <input type="text" class="form-control w-100" name="name" id="name" value="{{ old('name') }}">
                            <div class="text-danger" id="error-name"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="duration">{{ __('Duration') }}</label>
                        <select class="form-control w-100" name="duration" id="duration">
                            <option value="">{{ __('Select Duration')}}</option>
                            <option value="1 Month" {{ old('duration') == '1 Month' ? 'selected' : '' }}>{{ __('1 Month')}}</option>
                            <option value="3 Months" {{ old('duration') == '3 Months' ? 'selected' : '' }}>{{ __('3 Months')}}</option>
                            <option value="6 Months" {{ old('duration') == '6 Months' ? 'selected' : '' }}>{{ __('6 Months')}}</option>
                            <option value="12 Months" {{ old('duration') == '12 Months' ? 'selected' : '' }}>{{ __('12 Months')}}</option>
                        </select>
                            <div class="text-danger" id="error-duration"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="price">{{ __('Price') }} (INR)</label>
                        <input type="text" class="form-control w-100" name="price" id="price" value="{{ old('price') }}" readonly>
                            <div class="text-danger" id="error-price"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="objective">{{ __('Objective') }}</label>
                        <input type="text" class="form-control w-100" name="objective" id="objective" value="{{ old('objective') }}">
                        <div class="text-danger" id="error-objective"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="summary">{{ __('Summary') }}</label>
                        <input type="text" class="form-control w-100" name="summary" id="summary" value="{{ old('summary') }}">
                        <div class="text-danger" id="error-summary"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="paper_limit">{{ __('Paper Submission Limit') }}</label>
                        <input type="text" class="form-control w-100" name="paper_limit" id="paper_limit" value="{{ old('paper_limit') }}">
                            <div class="text-danger" id="error-paper_limit"></div>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="download_limit">{{ __('Download Limit') }}</label>
                        <input type="text" class="form-control w-100" name="download_limit" id="download_limit" value="{{ old('download_limit') }}">
                            <div class="text-danger" id="error-download_limit"></div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-11">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card">
                <h5 class="card-title mb-3 fw-bold">{{ __('View Subscription Plans') }}</h5>
                <div class="table-responsive">
                    <table class="table table-bordered border-dark-subtle table-hover fs-6" id="plansTable">
                        <thead class="custom-header">
                            <tr>
                                <th>{{ __('Plan Name') }}</th>
                                <th>{{ __('Duration') }}</th>
                                <th>{{ __('Price') }} (INR)</th>
                                <th>{{ __('Objective') }}</th>
                                <th>{{ __('Summary') }}</th>
                                <th>{{ __('Paper Limit') }}</th>
                                <th>{{ __('Download Limit') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($plans as $plan)
                                <tr data-id="{{ $plan->id }}">
                                    <td>{{ $plan->name }}</td>
                                    <td>{{ $plan->duration }}</td>
                                    <td>₹{{$plan->price }}</td>
                                    <td>{{ $plan->objective }}</td>
                                    <td>{{ $plan->summary }}</td>
                                    <td>{{ $plan->paper_limit }}</td>
                                    <td>{{ $plan->download_limit }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-success edit-btn mb-1" data-id="{{ $plan->id }}">
                                            {{ __('Edit')}}
                                        </button>
                                        <form method="POST" action="{{ route('admin.subscription.delete', $plan->id) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger del-btn mb-1" onclick="return confirm('Are you sure?')">
                                                {{ __('Delete')}}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
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
    document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('subscriptionForm');
    const priceInput = document.getElementById('price');
    const durationSelect = document.getElementById('duration');
    const planIdInput = document.getElementById('planId');

    const priceMap = {
        "1 Month": 850,
        "3 Months": 2100,
        "6 Months": 3800,
        "12 Months": 5500
    };

    // Update price based on duration selection
    durationSelect.addEventListener('change', function () {
        const selectedDuration = durationSelect.value;
        priceInput.value = priceMap[selectedDuration] || '';
    });

    // Handle edit button clicks
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            const planId = this.getAttribute('data-id');
            const row = this.closest('tr');
            
            // Populate form with plan data
            planIdInput.value = planId;
            document.getElementById('name').value = row.cells[0].textContent;
            document.getElementById('duration').value = row.cells[1].textContent;
            document.getElementById('price').value = row.cells[2].textContent.replace('₹', '').trim();
            document.getElementById('objective').value = row.cells[3].textContent;
            document.getElementById('summary').value = row.cells[4].textContent;
            document.getElementById('paper_limit').value = row.cells[5].textContent;
            document.getElementById('download_limit').value = row.cells[6].textContent;
            
            // Update form action and method for editing
            form.action = `{{ route('admin.subscription.update', ':id') }}`.replace(':id', planId);
            form.querySelector('input[name="_method"]').value = 'PUT';
        });
    });

    // Handle form submission
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        // Clear previous errors
        ['name', 'duration', 'price', 'objective', 'summary', 'paper_limit', 'download_limit'].forEach(field => {
            const errorDiv = document.getElementById('error-' + field);
            if (errorDiv) errorDiv.textContent = '';
        });

        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => { throw data; });
            }
            return response.json();
        })
        .then(data => {
            Swal.fire({
                title: '{{ __("Success!") }}',
                text: planIdInput.value ? '{{ __("Plan updated successfully!") }}' : '{{ __("Plan created successfully!") }}',
                icon: 'success',
                timer: 2000
            }).then(() => {
                form.reset();
                form.action = "{{ route('admin.subscription.store') }}";
                form.querySelector('input[name="_method"]').value = 'POST';
                planIdInput.value = '';
                window.location.reload();
            });
        })
        .catch(error => {
            // Display validation errors below each field
            if (error.errors) {
                Object.keys(error.errors).forEach(function (key) {
                    const errorDiv = document.getElementById('error-' + key);
                    if (errorDiv) {
                        errorDiv.textContent = error.errors[key][0];
                    }
                });
            } else {
                Swal.fire({
                    title: '{{ __("Error!") }}',
                    text: error.message || '{{ __("Something went wrong. Please try again.") }}',
                    icon: 'error',
                    timer: 5000
                });
            }
        });
    });
});
</script>
@endpush