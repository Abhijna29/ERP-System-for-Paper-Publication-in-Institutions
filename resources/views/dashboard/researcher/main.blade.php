{{-- Researcher Dashboard --}}
@extends('layouts.researcher')

@section('content')
<div class="row g-4 mb-4">
    {{--Submissions --}}
    @if($subscriptionExpired)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            Your subscription has expired. <a href="{{ route('subscription.plans') }}">Renew now</a> to continue accessing content.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="col-lg-4 col-12" style="z-index: 10;">
        <div class="card card-hover bg-info border-0 shadow-sm text-white">
            <div class="filter position-absolute end-0 me-3 mt-3">
                <a href="#" class="text-dark" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-ellipsis"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow shadow">
                    <li class="dropdown-header"><h6>{{ __('FILTER')}}</h6></li>
                    <li><a class="dropdown-item" href="{{ route('researcher.dashboard', ['filter' => 'today']) }}">{{ __('Today') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('researcher.dashboard', ['filter' => 'month']) }}">{{ __('This Month') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('researcher.dashboard', ['filter' => 'year']) }}">{{ __('This Year') }}</a></li>
                </ul>
            </div>
            <div class="card-body">
                <h5 class="pb-3">{{ __('Submissions') }}
                    <span class="fs-4">|</span>
                    <span class="fs-6">
                        @if ($filter == 'today') {{ __('Today') }}
                        @elseif ($filter == 'year') {{ __('This Year') }}
                        @else {{ __('This Month') }}
                        @endif
                    </span>
                </h5>
                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle bg-primary-subtle fs-4 text-primary d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                    <div class="ms-3">
                        <h4>{{ $totalSubmitted  }}</h4>
                        <span class="fw-bold">{{ abs($submittedGrowth)}}%</span>
                        <span>
                            {{ $submittedGrowth >= 0 ? __('increase') : __('decrease') }}
                        </span>
                    </div>
                    <div class="ms-auto fs-2">
                        <i class="fa-solid {{ $submittedGrowth >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Accepted Papers --}}
    <div class="col-lg-4 col-12" style="z-index: 9;">
        <div class="card card-hover bg-success border-0 shadow-sm text-white">
            <div class="filter position-absolute end-0 me-3 mt-3">
                <a href="#" class="text-dark" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-ellipsis"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow shadow">
                    <li class="dropdown-header"><h6>{{ __('FILTER')}}</h6></li>
                    <li><a class="dropdown-item" href="{{ route('researcher.dashboard', ['filter' => 'today']) }}">{{ __('Today') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('researcher.dashboard', ['filter' => 'month']) }}">{{ __('This Month') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('researcher.dashboard', ['filter' => 'year']) }}">{{ __('This Year') }}</a></li>
                </ul>
            </div>
            <div class="card-body">
                <h5 class="pb-3">{{ __('Published Works') }}
                    <span class="fs-4">|</span>
                    <span class="fs-6">
                        @if ($filter == 'today') {{ __('Today') }}
                        @elseif ($filter == 'year') {{ __('This Year') }}
                        @else {{ __('This Month') }}
                        @endif
                    </span>
                </h5>
                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle bg-success-subtle fs-4 text-success d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-check-circle"></i>
                    </div>
                    <div class="ms-3">
                        <h4>{{ $totalAccepted }}</h4>
                        <span class="fw-bold">{{ abs($acceptedGrowth)}}%</span>
                        <span>
                            {{ $acceptedGrowth >= 0 ? __('increase') : __('decrease') }}
                        </span>
                    </div>
                    <div class="ms-auto fs-2">
                        <i class="fa-solid {{ $acceptedGrowth >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- payments --}}
    <div class="col-lg-4 col-12" style="z-index: 1;">
        <div class="card card-hover bg-warning border-0 shadow-sm text-white">
            <div class="filter position-absolute end-0 me-3 mt-3">
                <a href="#" class="text-dark" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-ellipsis"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow shadow">
                    <li class="dropdown-header"><h6>{{ __('FILTER')}}</h6></li>
                    <li><a class="dropdown-item" href="{{ route('researcher.dashboard', ['filter' => 'today']) }}">{{ __('Today') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('researcher.dashboard', ['filter' => 'month']) }}">{{ __('This Month') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('researcher.dashboard', ['filter' => 'year']) }}">{{ __('This Year') }}</a></li>
                </ul>
            </div>
            <div class="card-body">
                <h5 class="pb-3">{{ __('Payments') }}
                    <span class="fs-4">|</span>
                    <span class="fs-6">
                        @if ($filter == 'today') {{ __('Today') }}
                        @elseif ($filter == 'year') {{ __('This Year') }}
                        @else {{ __('This Month') }}
                        @endif
                    </span>
                </h5>
                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle bg-warning-subtle fs-4 text-warning d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-indian-rupee-sign"></i>
                    </div>
                    <div class="ms-3">
                        <h4>₹{{ number_format($payments, 0) }}</h4>
                        <span class="fw-bold">{{ abs($paymentsGrowth)}}%</span>
                        <span>
                            {{ $paymentsGrowth >= 0 ? __('increase') : __('decrease') }}
                        </span>
                    </div>
                    <div class="ms-auto fs-2">
                        <i class="fa-solid {{ $paymentsGrowth >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card bg-white border-0 shadow-sm">
            <div class="card-body">
                <h5 class="pb-3 card-title fw-bold">
                    {{ __('Activity Report') }}
                </h5>
                <div id="chart">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var chartOptions = {
            series: [
                { name: "{{ __('Submissions')}}", data: @json($chartSubmissions) },
                { name: "{{ __('Payments')}}", data: @json($chartPayments) },

            ],
            chart: {
                type: "area",
                height: 350,
                zoom: { enabled: false },
                toolbar: { show: false }
            },
            colors: ["#0d6efd", "#ffc107" ],
            dataLabels: { enabled: false },
            markers: { size: 4 },
            fill: {
                type: "gradient",
                gradient: {
                    opacityFrom: 0.3,
                    opacityTo: 0.1
                }
            },
            stroke: { width: 2 },
            xaxis: {
                categories: @json($categories),
                title: { text: '{{ __('Last 10 days')}}' },
            },
            tooltip: {
                shared: true,
                intersect: false
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right'
            },  
            responsive: [{
                breakpoint: 768,
                options: {
                    chart: { height: 300 },
                    legend: { position: "bottom" },
                }
            }]
        };

        new ApexCharts(document.querySelector("#chart"), chartOptions).render();
    });
</script> 
@endpush