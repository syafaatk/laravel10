<?php

namespace App\Http\Controllers;

use App\Models\UserOrderDetail;
use App\Models\LunchEventUserOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserOrderDetailController extends Controller
{
    public function create(LunchEventUserOrder $lunchEventUserOrder)
    {
        // Ensure the authenticated user can create order details for this order
        if (Auth::id() !== $lunchEventUserOrder->user_id && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        return view('user-order-details.create', compact('lunchEventUserOrder'));
    }

    public function store(Request $request, LunchEventUserOrder $lunchEventUserOrder)
    {
        // Ensure the authenticated user can store order details for this order
        if (Auth::id() !== $lunchEventUserOrder->user_id && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $subtotal = $request->quantity * $request->price;

        UserOrderDetail::create([
            'lunch_event_user_order_id' => $lunchEventUserOrder->id,
            'item_name' => $request->item_name,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'subtotal' => $subtotal,
            'notes' => $request->notes,
        ]);

        // note didapat dari rangkuman pesanan , contoh "Makanan : Nasi Goreng 1 Porsi, Ayam Goreng 1 Porsi, Minuman : Es Jeruk 1 Gelas"
        // jika type adalah makanan maka porsi , jika minuman maka gelas
        $notes = '';
        $foodItems = [];
        $drinkItems = [];

        foreach ($lunchEventUserOrder->orderDetails as $detail) {
            // Simple heuristic to categorize as food or drink
            // This can be improved with a 'type' column in user_order_details or a more sophisticated categorization
            if ($detail->type == 'makanan') {
                $foodItems[] = $detail->item_name . ' ' . $detail->quantity . ' Porsi';
            } elseif ($detail->type == 'minuman') {
                $drinkItems[] = $detail->item_name . ' ' . $detail->quantity . ' Gelas';
            }
        }

        if (!empty($foodItems)) {
            $notes .= 'Makanan : ' . implode(', ', $foodItems);
        }
        if (!empty($drinkItems)) {
            if (!empty($notes)) {
                $notes .= ', ';
            }
            $notes .= 'Minuman : ' . implode(', ', $drinkItems);
        }
        $request->merge(['notes' => $notes]);
        
        

        // Update total_price in LunchEventUserOrder
        $lunchEventUserOrder->total_price = $lunchEventUserOrder->orderDetails()->sum('subtotal');
        $lunchEventUserOrder->quantity = $lunchEventUserOrder->orderDetails()->sum('quantity');
        $lunchEventUserOrder->notes = $notes;
        $lunchEventUserOrder->save();

        return redirect()->route('user-order-details.create', $lunchEventUserOrder->id)
                         ->with('success', 'Menu item added to your order successfully.');
    }

    public function edit(UserOrderDetail $userOrderDetail)
    {
        // Ensure the authenticated user can edit this order detail
        if (Auth::id() !== $userOrderDetail->order->user_id && !Auth::user()->hasRole('admin')) {
            abort(403);
        }
        return view('user-order-details.edit', compact('userOrderDetail'));
    }

    public function update(Request $request, UserOrderDetail $userOrderDetail)
    {
        // Ensure the authenticated user can update this order detail
        if (Auth::id() !== $userOrderDetail->order->user_id && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $subtotal = $request->quantity * $request->price;

        $userOrderDetail->update([
            'item_name' => $request->item_name,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'subtotal' => $subtotal,
            'notes' => $request->notes,
        ]);

        // Update total_price in LunchEventUserOrder
        $lunchEventUserOrder = $userOrderDetail->order;
        $lunchEventUserOrder->total_price = $lunchEventUserOrder->orderDetails()->sum('subtotal');
        $lunchEventUserOrder->save();

        return redirect()->route('lunch-event-user-orders.show', $userOrderDetail->lunch_event_user_order_id)
                         ->with('success', 'Menu item updated successfully.');
    }

    public function destroy(UserOrderDetail $userOrderDetail)
    {
        // Ensure the authenticated user can delete this order detail
        if (Auth::id() !== $userOrderDetail->order->user_id && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $lunchEventUserOrder = $userOrderDetail->order;
        $userOrderDetail->delete();

        //        // Update total_price in LunchEventUserOrder
        $lunchEventUserOrder->total_price = $lunchEventUserOrder->orderDetails()->sum('subtotal');
        $lunchEventUserOrder->quantity = $lunchEventUserOrder->orderDetails()->sum('quantity');
        $lunchEventUserOrder->save();

        return redirect()->route('user-order-details.create', $lunchEventUserOrder->id)
                         ->with('success', 'Menu item deleted successfully.');
    }
}
