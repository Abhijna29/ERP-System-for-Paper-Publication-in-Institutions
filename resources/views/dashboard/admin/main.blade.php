@extends('layouts.admin')

@section('content')
<div class="row g-4 mb-4">
    {{--Sales card --}}
    <div class="col-lg-4 col-12" style="z-index: 10;">
        <div class="card card-hover bg-info border-0 shadow-sm text-white">
            <div class="filter position-absolute end-0 me-3 mt-3">
                <a href="#" class="text-dark" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-ellipsis"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow shadow ">
                    <li class="dropdown-header"> <h6>{{ __('FILTER')}}</h6> </li>
                    <li><a class="dropdown-item" href="{{ route('admin.dashboard', ['filter' => 'today']) }}">{{ __('Today') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.dashboard', ['filter' => 'month']) }}">{{ __('This Month') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.dashboard', ['filter' => 'year']) }}">{{ __('This Year') }}</a><li>
                </ul>
            </div>
            <div class="card-body">
                <h5 class="pb-3"> 
                    {{ __('Sales')}}
                    <span class="fs-4">| </span>
                    <span class="fs-6">
                        @if ($filter == 'today') {{ __('Today') }}
                        @elseif ($filter == 'year') {{ __('This Year') }}
                        @else {{ __('This Month') }}
                        @endif
                    </span>
                </h5>
                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle bg-primary-subtle fs-4 text-primary d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                    <div class="ms-3">
                        <h4>{{ $sales }}</h4>
                        <span class="fw-bold">{{ abs($salesGrowth) }}%</span> 
                        <span>
                            {{ $salesGrowth >= 0 ? __('increase') : __('decrease') }}
                        </span>
                    </div>
                    <div class="ms-auto fs-2">
                        <i class="fa-solid {{ $salesGrowth >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Revenue card --}}
    <div class="col-lg-4 col-12" style="z-index: 9;">
        <div class="card card-hover bg-success border-0 shadow-sm text-white">
            <div class="filter position-absolute end-0 me-3 mt-3">
                <a href="#" class="text-dark" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-ellipsis"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow shadow ">
                    <li class="dropdown-header"> <h6>{{ __('FILTER')}}</h6> </li>
                    <li><a class="dropdown-item" href="{{ route('admin.dashboard', ['filter' => 'today']) }}">{{ __('Today') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.dashboard', ['filter' => 'month']) }}">{{ __('This Month') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.dashboard', ['filter' => 'year']) }}">{{ __('This Year') }}</a></li>
                </ul>
            </div>
            <div class="card-body">
                <h5 class="pb-3"> {{ __('Revenue')}}
                    <span class="fs-4">| </span>
                    <span class="fs-6">
                        @if ($filter == 'today') {{ __('Today') }}
                        @elseif ($filter == 'year') {{ __('This Year') }}
                        @else {{ __('This Month') }}
                        @endif
                    </span>
                </h5>
                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle bg-success-subtle fs-4 text-success d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-indian-rupee-sign"></i>
                    </div>
                    <div class="ms-3">
                        <h4>₹{{ number_format($revenue, 0) }}</h4>
                        <span class="fw-bold">{{ abs($revenueGrowth) }}%</span> 
                        <span>
                            {{ $revenueGrowth >= 0 ? __('increase') : __('decrease') }}
                        </span>
                    </div>
                    <div class="ms-auto fs-2">
                        <i class="fa-solid {{ $revenueGrowth >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                    </div>
                </div>
            </div>

        </div>
    </div> 

{{-- Customer card --}}
    <div class="col-lg-4 col-12" style="z-index: 1;">
        <div class="card card-hover bg-warning border-0 shadow-sm text-white">
            <div class="filter position-absolute end-0 me-3 mt-3">
                <a href="#" class="text-dark" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-ellipsis"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow shadow ">
                    <li class="dropdown-header"> <h6>{{ __('FILTER')}}</h6> </li>
                    <li><a class="dropdown-item" href="{{ route('admin.dashboard', ['filter' => 'today']) }}">{{ __('Today') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.dashboard', ['filter' => 'month']) }}">{{ __('This Month') }}</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.dashboard', ['filter' => 'year']) }}">{{ __('This Year') }}</a></li>
                </ul>
            </div>
            <div class="card-body">
                <h5 class="pb-3"> {{ __('Customers')}}  
                    <span class="fs-4">| </span>
                    <span class="fs-6">
                        @if ($filter == 'today') {{ __('Today') }}
                        @elseif ($filter == 'year') {{ __('This Year') }}
                        @else {{ __('This Month') }}
                        @endif
                    </span>
                </h5>
                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle bg-warning-subtle fs-4 text-warning d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div class="ms-3">
                        <h4>{{ $customers }}</h4>
                        <span class="fw-bold">{{ abs($customerGrowth)}}%</span> 
                        <span>
                            {{ $customerGrowth >= 0 ? __('increase') : __('decrease') }}
                        </span>
                    </div>
                    <div class="ms-auto fs-2">
                        <i class="fa-solid {{ $customerGrowth >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>        
</div>

{{-- Reports card --}}
    <div class="row">
        <div class="col-12">
            <div class="card bg-white border-0 shadow-sm">
                <div class="filter position-absolute end-0 me-3 mt-3">
                    <a href="#" class="text-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-ellipsis"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow shadow ">
                        <li class="dropdown-header"> <h6>{{ __('FILTER')}}</h6> </li>
                        <li><a class="dropdown-item" href="{{ route('admin.dashboard', ['filter' => 'today']) }}">{{ __('Today') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.dashboard', ['filter' => 'month']) }}">{{ __('This Month') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.dashboard', ['filter' => 'year']) }}">{{ __('This Year') }}</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <h5 class="pb-3 card-title fw-bold">
                    {{ __('Activity Report') }}
                    </h5>
                    <div id="chart"></div>
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
                { name: "{{ __('Sales') }}", data: @json($chartSales) },
                { name: "{{ __('Revenue') }}", data: @json($chartRevenue) },
                { name: "{{ __('Customers') }}", data: @json($chartCustomers) },

            ],
            chart: {
                type: "area",
                height: 350,
                zoom: { enabled: false },
                toolbar: { show: false }
            },
            colors: ["#0d6efd", "#198754", "#ffc107"],
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
                title: { text: "{{ __('Last 10 days') }}"},
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