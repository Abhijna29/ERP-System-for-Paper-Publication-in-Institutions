@extends('layouts.admin')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card">
                <h5 class="card-title mb-3 fw-bold">{{ __('View Institution Details') }}</h5>
                <div class="table-responsive">
                    <table class="table table-bordered border-dark-subtle table-hover fs-6">
                        <thead class="custom-header">
                            <tr>
                                <th>{{ __('Id')}}</th>
                                <th>{{ __('Institution Name')}}</th>
                                <th>{{ __('Type')}}</th>
                                <th>{{ __('Location')}}</th>
                                <th>{{ __('Year Established')}}</th>
                                <th>{{ __('Action')}}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection