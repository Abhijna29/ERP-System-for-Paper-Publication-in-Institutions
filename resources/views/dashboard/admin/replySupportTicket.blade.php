@extends('layouts.admin')

@section('content')
{{-- <div class="row g-4">
    <div class="col-12">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card">
                <h5 class="card-title mb-3 fw-bold">{{ __('Reply To Support Ticket') }}</h5>
                <div class="table-responsive">
                    <table class="table table-bordered border-dark-subtle table-hover fs-6">
                        <thead class="custom-header">
                            <tr>
                                <th>{{ __('Id')}}</th>
                                <th>{{ __('Acknowledgment')}}</th>
                                <th>{{ __('Guidance')}}</th>
                                <th>{{ __('Clarification')}}</th>
                                <th>{{ __('Status Update')}}</th>
                                <th>{{ __('Closing the Ticket')}}</th>
                                <th>{{ __('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<div class="row g-4">
    <div class="col-12">
        <div class="card bg-white border-0 rounded-4 shadow">
            <div class="card-body user-card">
                <h5 class="card-title mb-3 fw-bold">{{ __('Reply To Support Ticket') }}</h5>

                {{-- Ticket Info --}}
                <div class="mb-4">
                    <p><strong>Ticket ID:</strong> {{ $ticket->ticket_id }}</p>
                    <p><strong>Submitted By:</strong> {{ $ticket->submitter->name }}</p>
                    <p><strong>Issue:</strong> {{ $ticket->issue_description }}</p>
                </div>

                {{-- User Reply --}}
                @if($ticket->user_reply)
                <div class="alert alert-info">
                    <strong>User Reply:</strong><br>
                    {{ $ticket->user_reply }}
                </div>
                @endif

                {{-- Admin Reply Form --}}
                <form action="{{ route('admin.supportTicket.update', $ticket->ticket_id) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">{{ __('Acknowledgment') }}</label>
                        <input type="text" name="acknowledgment" class="form-control" value="{{ old('acknowledgment', $ticket->acknowledgment) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Guidance') }}</label>
                        <textarea name="guidance" class="form-control" rows="2">{{ old('guidance', $ticket->guidance) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Clarification') }}</label>
                        <textarea name="clarification" class="form-control" rows="2">{{ old('clarification', $ticket->clarification) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Status') }}</label>
                        <select name="status" class="form-select">
                            <option value="Open" {{ $ticket->status === 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="In Progress" {{ $ticket->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Resolved" {{ $ticket->status === 'Resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="Closed" {{ $ticket->status === 'Closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">Submit Reply</button>
                </form>
            </div>
        </div>
        <a href="{{route('admin.supportTickets')}}" class="btn btn-secondary ms-3 mt-3">{{ __('Back to Ticket List')}}</a>
    </div>
</div>

@endsection