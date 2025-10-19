<?php

namespace App\Http\Controllers;

use App\Models\LunchEvent;
use App\Models\MasterRestaurant;
use App\Models\LunchEventUserOrder;
use Illuminate\Http\Request;

class LunchEventController extends Controller
{
    public function index()
    {
        $lunchEvents = LunchEvent::all();
        return view('lunch-events.index', compact('lunchEvents'));
    }

    public function create()
    {
        // get restaurant
        $restaurants = MasterRestaurant::all();
        return view('lunch-events.create', compact('restaurants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'restaurant_id' => 'required|exists:master_restaurants,id',
            'event_date' => 'required|date',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:20480',
            'nota' => 'nullable|file|mimes:jpg,jpeg,png|max:20480',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('lunch_event_images', 'public');
            $request->merge(['image' => $imagePath]);
        }

        if ($request->hasFile('nota')) {
            $notaPath = $request->file('nota')->store('lunch_event_notas', 'public');
            $request->merge(['nota' => $notaPath]);
        }

        LunchEvent::create($request->all());

        return redirect()->route('lunch-events.index')
                         ->with('success', 'Lunch event created successfully.');
    }

    public function show(LunchEvent $lunchEvent)
    {
        // tampilkan data luncheventuserorders
        $lunchEventUserOrders = LunchEventUserOrder::where('lunch_event_id', $lunchEvent->id)->get();
        return view('lunch-events.show', compact('lunchEvent', 'lunchEventUserOrders'));
    }

    public function edit(LunchEvent $lunchEvent)
    {
        $restaurants = MasterRestaurant::all();
        return view('lunch-events.edit', compact('lunchEvent', 'restaurants'));
    }

    public function update(Request $request, LunchEvent $lunchEvent)
    {
        $request->validate([
            'name' => 'required|string',
            'restaurant_id' => 'required|exists:master_restaurants,id',
            'event_date' => 'required|date',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:20480',
            'nota' => 'nullable|file|mimes:jpg,jpeg,png|max:20480',
        ]);

        $imagePath = null;
        $notaPath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('lunch_event_images', 'public');
            $request->merge(['image' => $imagePath]);
        }

        if ($request->hasFile('nota')) {
            $notaPath = $request->file('nota')->store('lunch_event_notas', 'public');
            $request->merge(['nota' => $notaPath]);
        }

        // update with path
        $lunchEvent->update($request->all());
        if ($imagePath) {
            $lunchEvent->image = $imagePath;
        }
        if ($notaPath) {
            $lunchEvent->nota = $notaPath;
        }
        $lunchEvent->save();

        return redirect()->route('lunch-events.index')
                         ->with('success', 'Lunch event updated successfully.');
    }

    public function destroy(LunchEvent $lunchEvent)
    {
        $lunchEvent->delete();

        if ($lunchEvent->image) {
            \Storage::disk('public')->delete($lunchEvent->image);
        }
        if ($lunchEvent->nota) {
            \Storage::disk('public')->delete($lunchEvent->nota);
        }

        return redirect()->route('lunch-events.index')
                         ->with('success', 'Lunch event deleted successfully.');
    }
}