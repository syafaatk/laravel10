<?php

namespace App\Http\Controllers;

use App\Models\LunchEventUserOrder;
use App\Models\LunchEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LunchEventUserOrderController extends Controller
{
    public function create(LunchEvent $lunchEvent)
    {
        // langsung buat data user_id pada table lunch_event_user_orders
        $lunchEvent->user_id = Auth::id();
        // Cek apakah user sudah pernah memesan untuk event ini
        $existingOrder = LunchEventUserOrder::where('lunch_event_id', $lunchEvent->id)
                                            ->where('user_id', Auth::id())
                                            ->first();

        if ($existingOrder) {
            return redirect()->route('user-order-details.create', $existingOrder->id)
                             ->with('error', 'You have already placed an order for this event. You can edit your existing order.');
        }else{
            LunchEventUserOrder::create([
                'lunch_event_id' => $lunchEvent->id,
                'user_id' => Auth::id(),
                'status' => 'pending', // Default status
            ]);
            // get id luncheventuserorder yang baru dibuat
            $newOrder = LunchEventUserOrder::where('lunch_event_id', $lunchEvent->id)
                                            ->where('user_id', Auth::id())
                                            ->first();
            
            return redirect()->route('user-order-details.create', $newOrder->id)
                             ->with('success', 'Your order has been initiated. Please add menu items.');
        }
    }

    public function store(Request $request, LunchEvent $lunchEvent)
    {
        // This store method seems to be redundant if create already handles initial order creation.
        // It might be better to refactor so that `create` always creates the order and redirects to `user-order-details.create`.
        // Or, if `store` is meant for a different flow (e.g., a quick order with pre-defined items), it needs to be clarified.
        // For now, I'll assume it's a fallback or alternative.
        
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to place an order.');
        }

        // Check if an order already exists for this user and event
        $existingOrder = LunchEventUserOrder::where('lunch_event_id', $lunchEvent->id)
                                            ->where('user_id', Auth::id())
                                            ->first();

        if ($existingOrder) {
            return redirect()->route('user-order-details.create', $existingOrder->id)
                             ->with('error', 'You already have an active order for this event. Please edit it instead.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1', // This might be problematic if items are added individually later
            'total_price' => 'required|numeric|min:0', // This also might be problematic
            'notes' => 'nullable|string',
        ]);

        LunchEventUserOrder::create([
            'lunch_event_id' => $lunchEvent->id,
            'user_id' => Auth::id(),
            'quantity' => $request->quantity,
            'total_price' => $request->total_price,
            'notes' =>
            return redirect()->route('user-order-details.create', )
                             ->with('error', 'You have already placed an order for this event. You can edit your existing order.');
        }
    }

    public function store(Request $request, LunchEvent $lunchEvent)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        LunchEventUserOrder::create([
            'lunch_event_id' => $lunchEvent->id,
            'user_id' => Auth::id(),
            'quantity' => $request->quantity,
            'total_price' => $request->total_price,
            'notes' => $request->notes,
            'status' => 'pending', // Default status
        ]);

        return redirect()->route('user-order-details.create', $lunchEvent->id)
                         ->with('success', 'Your order has been placed successfully.');
    }

    public function show(LunchEventUserOrder $lunchEventUserOrder)
    {
        // Ensure the authenticated user can view this order
        if (Auth::id() !== $lunchEventUserOrder->user_id && !Auth::user()->hasRole('admin')) {
            abort(403);
        }
        return view('lunch-event-user-orders.show', compact('lunchEventUserOrder'));
    }

    public function edit(LunchEventUserOrder $lunchEventUserOrder)
    {
        // Only the owner or admin can edit
        if (Auth::id() !== $lunchEventUserOrder->user_id && !Auth::user()->hasRole('admin')) {
            abort(403);
        }
        return view('lunch-event-user-orders.edit', compact('lunchEventUserOrder'));
    }

    public function update(Request $request, LunchEventUserOrder $lunchEventUserOrder)
    {
        // Only the owner or admin can update
        if (Auth::id() !== $lunchEventUserOrder->user_id && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|integer|min:0',
            'notes' => 'nullable|string',
            'status' => 'required|string|in:pending,confirmed,cancelled', // Allow status update
        ]);

        $lunchEventUserOrder->update($request->all());

        return redirect()->route('lunch-events.show', $lunchEventUserOrder->lunchEvent->id)
                         ->with('success', 'Order updated successfully.');
    }

    public function destroy(LunchEventUserOrder $lunchEventUserOrder)
    {
        // Only the owner or admin can delete
        if (Auth::id() !== $lunchEventUserOrder->user_id && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $lunchEventUserOrder->delete();

        return redirect()->route('lunch-events.show', $lunchEventUserOrder->lunchEvent->id)
                         ->with('success', 'Order deleted successfully.');
    }
}
