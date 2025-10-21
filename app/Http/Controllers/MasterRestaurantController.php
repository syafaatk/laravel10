<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterRestaurant;
use Illuminate\Support\Facades\Gate;

class MasterRestaurantController extends Controller
{
    public function index()
    {
        $masterRestaurants = MasterRestaurant::all();
        return view('admin.master-restaurants.index', compact('masterRestaurants'));
    }

    public function create()
    {
        if (!Gate::allows('create-restaurant')) {
            return redirect()->route('master-restaurants.index')->with('error', 'You are not authorized to create a restaurant.');
        }else{
            return view('admin.master-restaurants.create');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:20480',
            'menu_1' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:20480',
            'menu_2' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:20480',
            'menu_3' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:20480',
            'menu_4' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:20480',
            'menu_5' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:20480',
            'menu_6' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:20480',
            'menu_7' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:20480'
        ]);

        $data = $request->all();

        $fileFields = ['image', 'menu_1', 'menu_2', 'menu_3', 'menu_4', 'menu_5', 'menu_6', 'menu_7'];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $fileName = time() . '_' . $request->file($field)->getClientOriginalName();
                $request->file($field)->storeAs('public/restaurants', $fileName);
                $data[$field] = $fileName;
            }
        }

        MasterRestaurant::create($data);

        return redirect()->route('master-restaurants.index')
                         ->with('success', 'Restaurant created successfully.');
    }

    public function show(MasterRestaurant $masterRestaurant)
    {
        return view('admin.master-restaurants.show', compact('masterRestaurant'));
    }

    public function edit(MasterRestaurant $masterRestaurant)
    {
        if (!Gate::allows('edit-restaurant')) {
            return redirect()->route('master-restaurants.index')->with('error', 'You are not authorized to edit a restaurant.');
        }else{
            return view('admin.master-restaurants.edit', compact('masterRestaurant'));
        }
    }

    public function update(Request $request, MasterRestaurant $masterRestaurant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:20480',
            'menu_1' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:20480',
            'menu_2' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:20480',
            'menu_3' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:20480',
            'menu_4' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:20480',
            'menu_5' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:20480',
            'menu_6' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:20480',
            'menu_7' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:20480'
        ]);

        $data = $request->all();

        $fileFields = ['image', 'menu_1', 'menu_2', 'menu_3', 'menu_4', 'menu_5', 'menu_6', 'menu_7'];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file if it exists
                if ($masterRestaurant->$field && \Storage::exists('public/restaurants/' . $masterRestaurant->$field)) {
                    \Storage::delete('public/restaurants/' . $masterRestaurant->$field);
                }

                $fileName = time() . '_' . $request->file($field)->getClientOriginalName();
                $request->file($field)->storeAs('public/restaurants', $fileName);
                $data[$field] = $fileName;
            } else {
                // If no new file is uploaded, retain the old one
                $data[$field] = $masterRestaurant->$field;
            }
        }
        $masterRestaurant->update($data);

        return redirect()->route('master-restaurants.index')
                         ->with('success', 'Restaurant updated successfully.');
    }

    public function destroy(MasterRestaurant $masterRestaurant)
    {
        if (!Gate::allows('delete-restaurant')) {
            return redirect()->route('master-restaurants.index')->with('error', 'You are not authorized to delete a restaurant.');
        }else{
            $masterRestaurant->delete();
            // Delete associated files
            $fileFields = ['image', 'menu_1', 'menu_2', 'menu_3', 'menu_4', 'menu_5', 'menu_6', 'menu_7'];
            foreach ($fileFields as $field) {
                if ($masterRestaurant->$field && \Storage::exists('public/restaurants/' . $masterRestaurant->$field)) {
                    \Storage::delete('public/restaurants/' . $masterRestaurant->$field);
                }
            }
            return redirect()->route('master-restaurants.index')
                            ->with('success', 'Restaurant deleted successfully.');
        }
    }
    
}