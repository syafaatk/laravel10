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
            return redirect()->route('user-order-details.create', $lunchEvent->id)
                             ->with('error', 'You have already placed an order for this event. You can edit your existing order.');
        }else{
            LunchEventUserOrder::create([
            'lunch_event_id' => $lunchEvent->id,
            'user_id' => Auth::id(),
            'status' => 'pending', // Default status
        ]);
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
