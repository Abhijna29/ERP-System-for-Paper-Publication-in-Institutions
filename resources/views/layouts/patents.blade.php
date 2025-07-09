@extends('layouts.admin')

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-md-10">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card px-5">
                <h5 class="card-title mb-3 fw-bold">@yield('name')</h5>

                <form id="form">
                    <div class="row g-2 mb-3">
                        <label for="investor">{{ __('Investors Name')}}:</label>
                        <div class="col-lg-3">
                            <input type="text" class="form-control w-100" placeholder="{{ __('First Name')}}">
                        </div>
                        <div class="col-lg-3">
                            <input type="text" class="form-control w-100" placeholder="{{ __('Middle Name') }}">
                        </div>
                        <div class="col-lg-3">
                            <input type="text" class="form-control w-100" placeholder="{{ __('Last Name')}}">
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="title">{{ __('Work Title')}}</label>
                        <input class="form-control w-100" name="title" id="title">
                        <div id="error-title" class="text-danger"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="desc">{{ __('Work Description')}}:</label>
                        <textarea type="text" class="form-control w-100" name="desc" id="desc"></textarea>                    
                        <div id="error-desc" class="text-danger"></div>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="name">{{ __('Year')}}:</label>
                        <input type="text" class="form-control w-100" name="name" id="year">                    
                        <div id="error-year" class="text-danger"></div>
                    </div>
                    <div class="col-12 mb-3">
                        @yield('number')
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
    const form = document.getElementById("form");
    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const fieldNames = {
       title: @json(__('Work Title')),
    desc: @json(__('Work Description')),
    year: @json(__('Year')),
    number: @json(__('Publication Number')),
    grant: @json(__('Grant Patent Number')),
    copy_number: @json(__('Copyright Number')),
    grant_number: @json(__('Grant Copyright Number')),
    trade_number: @json(__('Trade Mark Number')),
    grant_trade_number: @json(__('Grant Trade Mark Number')),
    grant_design_number: @json(__('Grant Design Number'))
    };

        let hasError = false;
        Object.keys(fieldNames).forEach((id) => {
            const input = document.getElementById(id);
            const error = document.getElementById(`error-${id}`);

            if (!input || !error) return;
            
            input.addEventListener("input", () => {
                if (input.value.trim()) {
                    error.textContent = "";
                }
            });

            if (!input.value.trim()) {
                error.textContent = `{{ __('Please Enter The')}} ${fieldNames[id]}`;
                hasError = true;
                hasError = true;
            } else {
                error.textContent = "";
            }
        });
        if (hasError) return;
        form.reset();
        Swal.fire(@json(__('Success!')), @json(__('Journal saved successfully!')), "success");
    });
</script>
@endpush