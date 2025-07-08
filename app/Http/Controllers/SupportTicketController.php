<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\User;
use App\Notifications\AdminTicketReplyNotification;
use App\Notifications\UserTicketReplyNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SupportTicketController extends Controller
{

    public function create()
    {
        $role = Auth::user()->role;
        return view('dashboard.supportTickets.create', compact('role'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'issue_description' => 'required|string|min:10',
        ]);

        $ticket = SupportTicket::create([
            'ticket_id' => 'TICKET-' . strtoupper(Str::random(8)),
            'issue_description' => $request->issue_description,
            'submitted_by' => Auth::user()->id,
            'status' => 'Open',
        ]);

        // Notify admin
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new UserTicketReplyNotification($ticket, 'New Support Ticket Submitted'));
        }

        return redirect()->route('supportTickets.index')->with('success', 'Support ticket submitted successfully.');
    }

    public function userTickets()
    {
        $role = Auth::user()->role;
        $tickets = SupportTicket::where('submitted_by', Auth::user()->id)->latest()->get();
        return view('dashboard.supportTickets.index', compact('tickets', 'role'));
    }

    public function userReply(Request $request, $ticketId)
    {
        $request->validate([
            'user_reply' => 'required|string|min:3',
        ]);

        $ticket = SupportTicket::where('ticket_id', $ticketId)
            ->where('submitted_by', Auth::user()->id)
            ->firstOrFail();

        $ticket->update([
            'user_reply' => $request->user_reply,
            'status' => 'In Progress',
        ]);
        $admin = User::where('role', 'admin')->first();

        if ($admin) {
            $admin->notify(new UserTicketReplyNotification($ticket));
        }

        return back()->with('success', 'Your reply has been sent to the admin.');
    }


    //Admin module
    public function index()
    {
        $tickets = SupportTicket::with(['submitter'])->latest()->get();
        return view('dashboard.admin.supportTicket', compact('tickets'));
    }

    public function showReplyForm($ticketId)
    {
        $ticket = SupportTicket::where('ticket_id', $ticketId)->firstOrFail();
        return view('dashboard.admin.replySupportTicket', compact('ticket'));
    }

    public function update(Request $request, $ticketId)
    {
        $ticket = SupportTicket::where('ticket_id', $ticketId)->firstOrFail();

        $ticket->update([
            'acknowledgment' => $request->input('acknowledgment'),
            'guidance' => $request->input('guidance'),
            'clarification' => $request->input('clarification'),
            'status' => $request->status,
        ]);
        $ticket->submitter->notify(new AdminTicketReplyNotification($ticket));

        return redirect()->route('admin.supportTickets')->with('success', 'Ticket updated successfully.');
    }
}
