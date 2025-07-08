@php
    // Map roles to layouts (adjust paths as per your project)
    $layouts = [
        'admin' => 'layouts.admin',
        'researcher' => 'layouts.researcher',
        'reviewer' => 'layouts.reviewer',
        'institution' => 'layouts.institution',
        'department' => 'layouts.department',
    ];

    // Pick layout or default to researcher layout
    $layout = $layouts[$role];
@endphp

@extends($layout)
@section('content')
<div class="container py-4">
    <div class="card bg-white border-0 rounded-4 shadow">
        <div class="card-body user-card">
            <h5 class="card-title mb-4">{{ __('My Support Tickets')}}</h5>

            @if($tickets->isEmpty())
                <div class="alert alert-info">{{ __('You haven\'t submitted any support tickets yet.')}}</div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered border-dark-subtle table-hover">
                        <thead class="custom-header">
                            <tr>
                                <th>{{ __('Ticket ID')}}</th>
                                <th>{{ __('Issue')}}</th>
                                <th>{{ __('Status')}}</th>
                                <th>{{ __('Reply')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                                <tr>
                                    <td>{{ $ticket->ticket_id }}</td>
                                    <td>{{ $ticket->issue_description }}</td>
                                    <td>{{ $ticket->status }}</td>
                                    <td>
                                        @if($ticket->acknowledgment || $ticket->guidance || $ticket->clarification)
                                            <strong>{{ __('Acknowledgment') }}:</strong> {{ $ticket->acknowledgment ?? '-' }}<br>
                                            <strong>{{ __('Guidance') }}:</strong> {{ $ticket->guidance ?? '-' }}<br>
                                            <strong>{{ __('Clarification') }}:</strong> {{ $ticket->clarification ?? '-' }}

                                            {{-- Show existing user reply if any --}}
                                            @if($ticket->user_reply)
                                                <div class="mt-2">
                                                    <strong>{{ __('Your Reply') }}:</strong> {{ $ticket->user_reply }}
                                                </div>
                                            @endif
                                            
                                            {{-- Reply form --}}
                                            @if ($ticket->status !=='Closed')
                                                <form action="{{ route('supportTickets.userReply', $ticket->ticket_id) }}" method="POST" class="mt-2">
                                                    @csrf
                                                    <div class="mb-2">
                                                        <textarea name="user_reply" class="form-control" rows="2" placeholder="Write your follow-up..." required></textarea>
                                                    </div>
                                                    <button type="submit" class="btn btn-sm btn-outline-primary">Send Reply</button>
                                                </form>
                                            @endif
                                        @else
                                            <em>{{ __('No reply yet') }}</em>
                                        @endif
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
@endsection
