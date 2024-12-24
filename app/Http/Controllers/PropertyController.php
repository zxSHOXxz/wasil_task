<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::latest()->paginate(10);
        return view('pages.properties.index', compact('properties'));
    }

    public function create()
    {
        return view('pages.properties.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price_per_night' => 'required|numeric|min:0',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:available,unavailable'
        ]);

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('properties', 'public');
                $images[] = $path;
            }
            $validated['images'] = $images;
        }

        Property::create($validated);

        return redirect()->route('properties.index')
            ->with('success', 'تم إضافة العقار بنجاح');
    }

    public function edit(Property $property)
    {
        return view('properties.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price_per_night' => 'required|numeric|min:0',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:available,unavailable'
        ]);

        if ($request->hasFile('images')) {
            // Delete old images
            if ($property->images) {
                foreach ($property->images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }

            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('properties', 'public');
                $images[] = $path;
            }
            $validated['images'] = $images;
        }

        $property->update($validated);

        return redirect()->route('properties.index')
            ->with('success', 'تم تحديث العقار بنجاح');
    }

    public function destroy(Property $property)
    {
        if ($property->images) {
            foreach ($property->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $property->delete();

        return redirect()->route('properties.index')
            ->with('success', 'تم حذف العقار بنجاح');
    }

    public function show(Property $property)
    {
        return view('pages.properties.show', compact('property'));
    }
}
