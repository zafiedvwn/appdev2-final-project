<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;

class TicketController extends Controller
{
    public function index()
    {
        return Ticket::all();
    }

    public function store(StoreTicketRequest $request)
    {
        $ticket = Ticket::create($request->validated());

        return response()->json($ticket, 201);
    }

    public function show(Ticket $ticket)
    {
        return $ticket;
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $ticket->update($request->validated());

        return response()->json($ticket, 200);
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return response()->json(null, 204);
    }

    // Method to view the ticket status of the authenticated user
    public function viewTicketStatus()
    {
        $user = Auth::user();

        // Fetch the ticket status for the authenticated user
        $ticketStatus = Ticket::where('user_id', $user->user_id)->first();

        if ($ticketStatus) {
            return response()->json($ticketStatus, 200);
        }

        return response()->json(['message' => 'Ticket status not found'], 404);
    }
}
