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

        // untuk menu item yang sudah ada tampilkan sebagai data select2 option
        $existingMenuItems = UserOrderDetail::whereHas('order', function($query) use ($lunchEventUserOrder) {
            $query->where('lunch_event_id', $lunchEventUserOrder->lunch_event_id);
        })
        ->select('item_name', 'price', 'type')
        ->distinct()
        ->get();
        $existingMenuItems = $existingMenuItems->map(function($item) {
            return [
                'item_name' => $item->item_name,
                'price' => $item->price,
                'type' => $item->type,
            ];
        });

        return view('user-order-details.create', compact('lunchEventUserOrder', 'existingMenuItems'));
    }

    public function store(Request $request, LunchEventUserOrder $lunchEventUserOrder)
    {
        // 1. Authorization & Validation
        if (Auth::id() !== $lunchEventUserOrder->user_id && !Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $request->validate([
            'item_name' => 'required|string|max:255',
            'type'      => 'required|in:makanan,minuman', // Pastikan type valid
            'quantity'  => 'required|integer|min:1',
            'price'     => 'required|numeric|min:0',
        ]);

        // 2. Create Order Detail
        UserOrderDetail::create([
            'lunch_event_user_order_id' => $lunchEventUserOrder->id,
            'type'                      => $request->type,
            'item_name'                 => $request->item_name,
            'quantity'                  => $request->quantity,
            'price'                     => $request->price,
            'subtotal'                  => $request->quantity * $request->price,
            'notes'                     => $request->notes, // Notes individual item (jika ada)
        ]);

        // 3. Refresh Data & Generate Summary Notes
        // Penting: Refresh detail agar item yang baru masuk ikut terhitung dalam loop
        $details = $lunchEventUserOrder->orderDetails()->get();
        
        $summaryNotes = [];
        $foodItems = [];
        $drinkItems = [];

        foreach ($details as $detail) {
            if ($detail->type == 'makanan') {
                $foodItems[] = "{$detail->item_name} {$detail->quantity} Porsi";
            } elseif ($detail->type == 'minuman') {
                $drinkItems[] = "{$detail->item_name} {$detail->quantity} Gelas";
            }
        }

        if (!empty($foodItems)) {
            $summaryNotes[] = 'Makanan : ' . implode(', ', $foodItems);
        }
        if (!empty($drinkItems)) {
            $summaryNotes[] = 'Minuman : ' . implode(', ', $drinkItems);
        }

        $finalNotes = implode(', ', $summaryNotes);

        // 4. Sync Total ke Parent Order
        $lunchEventUserOrder->update([
            'total_price' => $details->sum('subtotal'),
            'quantity'    => $details->sum('quantity'),
            'notes'       => $finalNotes,
        ]);

        return redirect()->route('user-order-details.create', $lunchEventUserOrder->id)
                        ->with('success', 'Menu berhasil ditambahkan ke pesanan.');
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
            'lunch_event_user_order_id' => $lunchEventUserOrder->id,
            'type' => $request->type,
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
