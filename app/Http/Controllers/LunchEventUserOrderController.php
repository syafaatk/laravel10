<?php

namespace App\Http\Controllers;

use App\Models\LunchEventUserOrder;
use App\Models\UserOrderDetail;
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

    public function updateItem(Request $request, $lunchEventId)
    {
        // Only admin can edit aggregated items
        if (!Auth::user() || !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $validated = $request->validate([
            'item_key' => 'required|string',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'order_type' => 'required|in:ditempat,bungkus',
            'type' => 'required|string|in:makanan,minuman',
        ]);

        $lunchEvent = LunchEvent::findOrFail($lunchEventId);
        list($oldItemName, $oldPrice) = explode('|', $validated['item_key']);

        // Update semua order details yang match dengan item lama
        UserOrderDetail::whereHas('order', function($q) use ($lunchEventId) {
            $q->where('lunch_event_id', $lunchEventId);
        })
        ->where('item_name', $oldItemName)
        ->where('price', $oldPrice)
        ->update([
            'item_name' => $validated['item_name'],
            'price' => $validated['price'],
            'notes' => $validated['order_type'],
            'type' => $validated['type'],
        ]);

        return redirect()->route('lunch-events.show', $lunchEvent)
                       ->with('success', 'Item updated successfully!');
    }

    public function destroyItem(Request $request, $lunchEventId)
    {
        // Only admin can delete aggregated items
        if (!Auth::user() || !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $validated = $request->validate([
            'item_key' => 'required|string',
        ]);

        $lunchEvent = LunchEvent::findOrFail($lunchEventId);
        list($itemName, $price) = explode('|', $validated['item_key']);

        UserOrderDetail::whereHas('order', function($q) use ($lunchEventId) {
            $q->where('lunch_event_id', $lunchEventId);
        })
        ->where('item_name', $itemName)
        ->where('price', $price)
        ->delete();

        return redirect()->route('lunch-events.show', $lunchEvent)
                       ->with('success', 'Item deleted successfully!');
    }
}
