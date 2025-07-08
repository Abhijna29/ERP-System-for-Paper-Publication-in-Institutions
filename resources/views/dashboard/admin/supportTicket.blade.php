@extends('layouts.admin')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card">
                <h5 class="card-title mb-3 fw-bold">{{ __('Support Ticket List') }}</h5>
                @if($tickets->isEmpty())
                    <div class="alert alert-info" role="alert">
                        {{ __('No support tickets found.') }}
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-bordered border-dark-subtle table-hover fs-6">
                            <thead class="custom-header">
                                <tr>
                                    <th>{{ __('Id')}}</th>
                                    <th>{{ __('Ticket ID')}}</th>
                                    <th>{{ __('Issue Description')}}</th>
                                    <th>{{ __('Submitted By')}}</th>
                                    <th>{{ __('Status')}}</th>
                                    {{-- <th>{{ __('Assigned To')}}</th> --}}
                                    <th>{{ __('User Reply') }}</th>
                                    <th>{{ __('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tickets as $ticket)
                                <tr>
                                    <td>{{ $ticket->id }}</td>
                                    <td>{{ $ticket->ticket_id }}</td>
                                    <td>{{ $ticket->issue_description }}</td>
                                    <td>{{ $ticket->submitter->name }}</td>
                                    <td>{{ $ticket->status }}</td>
                                    <td>
                                        @if($ticket->user_reply)
                                            {{ $ticket->user_reply }}
                                        @else
                                            <em>No reply from user</em>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.supportTicket.reply', $ticket->ticket_id) }}" class="btn btn-sm btn-primary">Reply</a>
                                    </td>
                                    
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
            </div>
        </div>
    </div>
</div>
@endsection