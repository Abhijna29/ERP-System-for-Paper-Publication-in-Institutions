{{-- Reviewer dashboard --}}
@extends('layouts.reviewer')

@section('content')
<div class="row g-4 mb-4">
    {{-- Completed Reviews --}}
    <div class="col-lg-6 col-12" style="z-index: 10;">
        <div class="card card-hover bg-success border-0 shadow-sm text-white">
            <div class="filter position-absolute end-0 me-3 mt-3">
                <a href="#" class="text-dark" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-ellipsis"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow shadow">
                    <li class="dropdown-header"><h6>{{ __('FILTER')}}</h6></li>
                    <li><a class="dropdown-item" href="{{ route('reviewer.dashboard', ['filter' => 'today']) }}">{{ __('Today') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('reviewer.dashboard', ['filter' => 'month']) }}">{{ __('This Month') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('reviewer.dashboard', ['filter' => 'year']) }}">{{ __('This Year') }}</a></li>
                </ul>
            </div>
            <div class="card-body">
                <h5 class="pb-3">{{ __('Reviews Completed') }}
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
                        <h4>{{ $totalCompleted }}</h4>
                        <span class="fw-bold">{{ abs($completedGrowth)}}%</span>
                        <span>
                            {{ $completedGrowth >= 0 ? __('increase') : __('decrease') }}
                        </span>
                    </div>
                    <div class="ms-auto fs-2">
                        <i class="fa-solid {{ $completedGrowth >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Assigned Reviews --}}
    <div class="col-lg-6 col-12" style="z-index: 9;">
        <div class="card card-hover bg-info border-0 shadow-sm text-white">
            <div class="filter position-absolute end-0 me-3 mt-3">
                <a href="#" class="text-dark" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-ellipsis"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow shadow">
                    <li class="dropdown-header"><h6>{{ __('FILTER')}}</h6></li>
                    <li><a class="dropdown-item" href="{{ route('reviewer.dashboard', ['filter' => 'today']) }}">{{ __('Today') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('reviewer.dashboard', ['filter' => 'month']) }}">{{ __('This Month') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('reviewer.dashboard', ['filter' => 'year']) }}">{{ __('This Year') }}</a></li>
                </ul>
            </div>
            <div class="card-body">
                <h5 class="pb-3">{{ __('Assigned Reviews') }}
                    <span class="fs-4">|</span>
                    <span class="fs-6">
                        @if ($filter == 'today') {{ __('Today') }}
                        @elseif ($filter == 'year') {{ __('This Year') }}
                        @else {{ __('This Month') }}
                        @endif
                    </span>
                </h5>
                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle bg-info-subtle fs-4 text-info d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                    <div class="ms-3">
                        <h4>{{ $assignedReviews }}</h4>
                        <span class="fw-bold">{{ abs($assignedGrowth)}}%</span>
                        <span>
                            {{ $assignedGrowth >= 0 ? __('increase') : __('decrease') }}
                        </span>
                    </div>
                    <div class="ms-auto fs-2">
                        <i class="fa-solid {{ $assignedGrowth >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
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
                { name: "{{ __('Reviews Completed')}}", data: @json($chartCompleted) },
                { name: "{{ __('Assigned Reviews')}}", data: @json($chartAssigned) },

            ],
            chart: {
                type: "area",
                height: 350,
                zoom: { enabled: false },
                toolbar: { show: false }
            },
            colors: [ "#198754","#0d6efd",],
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